<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ApiLog;
use App\Models\Agent;
use App\Models\MerchantApiHitLimit;
use App\Models\MerchantGateway;
use App\Models\MerchantInfo;
use App\Models\MerchantRollingReserve;
use App\Models\MerchantWallet;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use App\Services\ApiLoggerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaykuberController extends Controller
{
    private function encryptData($data, $secretKey) // Code added on 14-03-2025 by ketan
    {
        $iv = random_bytes(16); // Generate a 16-byte IV
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $secretKey, 0, $iv);
        return base64_encode($iv . $encrypted); // Combine IV + encrypted data
    }

    private function makePaykuberRequest($payload, $url, $apiToken) // Tested & Ready
    {
        $response = Http::withHeaders([
            'Authorization' => is_object($apiToken) ? $apiToken->token : $apiToken,
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
            'User-Agent' => 'PostmanRuntime/7.30.0'
        ])->post($url, $payload);
        return $response;
    }

    private function rollingReserve($merchantid, $transactionId, $amount, $newAmount, $chargePercent) // Tested & Ready
    {
        $reserveAmount = ((float)$newAmount * (float)$chargePercent) / 100.00;
        $pendingAmount = (float)$newAmount - $reserveAmount;
        $data = [
            'rolling_amount' => $reserveAmount,
            'pending_amount' => $pendingAmount
        ];
        if ($chargePercent > 0) {
            MerchantRollingReserve::create([
                'transaction_id' => $transactionId,
                'merchant_id' => $merchantid,
                'transaction_amount' => $amount,
                'reserve_amount' => $reserveAmount,
                'held_date' => Carbon::now(),
                'release_date' => Carbon::now()->addDays(7),
                'status' => 'held'
            ]);
        }
        return $data;
    }

    private function payoutRequest2(Request $request, MerchantInfo $merchant, MerchantGateway $merchantGateway, MerchantWallet $merchantWallet) // Tested & Ready
    {
        try {
            $apiToken = $request->attributes->get('api_token');
            // Updated on 20-03-2025 by Ketan Gupta Start
            $ptGateway = PaymentGateway::find($merchantGateway->payout_gateway_id);
            if (!$ptGateway) {
                return response()->json(['message' => 'Payment gateway configuration error! Please contact support.'], 403);
            }
            switch ($ptGateway->gateway_type) {
                case 'payout':
                case 'both':
                    break;
                default:
                    return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
            }
            // Updated on 20-03-2025 by Ketan Gupta End
            $amount = (float) $request->amount;
            $charges = 0.00;
            $gatewayCharge = 0.00;
            $gatewayChargeType = null;
            // Updated on 20-03-2025 by Ketan Gupta Start
            if ($amount >= 0 && $amount < $merchantGateway->payout_switch_amount) {
                $gatewayCharge = $merchantGateway->payout_charge ?? 0;
                $gatewayChargeType = $merchantGateway->payout_charge_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $charges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $charges = ($amount * $gatewayCharge) / 100.00;
                } else {
                    $charges = 0.0;
                }
            } elseif ($amount >= $merchantGateway->payout_switch_amount) {
                $gatewayCharge = $merchantGateway->payout_charge2 ?? 0;
                $gatewayChargeType = $merchantGateway->payout_charge2_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $charges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $charges = ($amount * $gatewayCharge) / 100.00;
                } else {
                    $charges = 0.0;
                }
            } else {
                $charges = 0.0;
            }
            $gst = 0.00;
            if($merchantGateway->gst_enabled === "yes"){
                $gst = ($charges * (float)$merchantGateway->gst_percentage) / 100.00;
            }
            $charges = $charges + $gst;
            // Updated on 20-03-2025 by Ketan Gupta End
            $penality = $merchant->payout_v_charge ?? 0.00;
            $failedHitsLimit = (($merchant->payout_failed_hits ?? 1) <= 0) ? 1 : $merchant->payout_failed_hits;
            $lastTransactions = WalletTransaction::where('type', 'debit')
                ->where('merchant_id', $merchant->merchant_id)
                ->orderBy('created_at', 'DESC')
                ->limit($failedHitsLimit)
                ->get();
            if ($lastTransactions->count() >= $failedHitsLimit) {
                foreach ($lastTransactions as $trxn) {
                    if ($trxn->status === 'completed' || $trxn->status === 'successful') {
                        $penality = 0.00;
                        break;
                    }
                }
                if ($penality > 0) {
                    $merchantWallet->update([
                        'balance' => $merchantWallet->balance - $penality
                    ]);
                }
            }
            $payableAmount = $amount + $charges;

            // Payload (callback data)
            $data = json_encode($request->all());
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($data, $secret);
            $logPayload = [
                'payload' => $request->all(),
                'signature' => $signature
            ];

            if ($merchantWallet->balance < $payableAmount) {
                return response()->json(['message' => 'Insufficient funds! Plese recharge your account and try again.'], 403);
            }
            // Updated on 20-03-2025 by Ketan Gupta Start
            // $charges = round(($amount * $gatewayCharge) / 100, 2);
            // if ($amount < 500) {
            //     $charges = 11.85;
            // }
            // Updated on 20-03-2025 by Ketan Gupta End
            ApiLoggerService::logEvent(
                '/v1/payout-request',
                'Payout Request',
                'Payout request generated for manual.',
                $logPayload
            );
            Transaction::create([
                'order_id' => $request->order_id,
                'gateway' => 'Paykuber',
                'api_token' => is_object($apiToken) ? $apiToken->token : $apiToken,
                'request_data' => $logPayload,
                'trx_type' => 'payout',
                'status' => 'queued'
            ]);
            // Updated on 20-03-2025 by Ketan Gupta Start
            WalletTransaction::create([
                'merchant_id' => $merchant->merchant_id,
                'amount' => $amount,
                'charge' => ($charges - $gst),
                'gst' => $gst,
                'gst_inc_charge' => $charges,
                'type' => 'debit',
                'transaction_id' => $request->order_id,
                'remarks' => $request->remarks,
                'utr' => null,
                'acc_no' => $request->account_number ?? null,
                'ifsc' => $request->bank_ifsc ?? null,
                'beneficiary_name' => $request->bene_name ?? null,
                'current_balance' => $merchantWallet->balance,
                'current_pending_balance' => $merchantWallet->pending_balance,
                'current_hold_balance' => $merchantWallet->roling_balance,
                'settlement_status' => null,
                'pt_gateway_charges' => 0,
                'pt_agent_commission' => 0,
                'status' => 'processing',
                'visibility' => 'visible'
            ]);
            // Updated on 20-03-2025 by Ketan Gupta End
            $responseData = [
                "status" => "SUCCESS",
                "data" => [
                    "txn_id" => $request->order_id,
                    "status" => "processing",
                    "amount" => $amount,
                    "remarks" => $request->remarks,
                    "order_id" => $request->order_id
                ]
            ];
            $oldBalance = $merchantWallet->balance;
            $oldRolingBalance = $merchantWallet->roling_balance;
            $merchantWallet->update([
                'balance' => $oldBalance - $payableAmount,
                'roling_balance' => $oldRolingBalance + $payableAmount
            ]);
            ApiLoggerService::logEvent(
                '/v1/payout-request',
                'Payout balance reserved',
                'Old balance: ' . $oldBalance . ', Old roling balance: ' . $oldRolingBalance . ' | New balance: ' . $merchantWallet->balance . ', New roling balance: ' . $merchantWallet->roling_balance,
                $logPayload,
                $responseData,
                'info'
            );
            if (filter_var($merchant->callback_url, FILTER_VALIDATE_URL)) {
                // Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->callback_url, $responseData);
                $merchantApiHitLimit = MerchantApiHitLimit::where('merchant_id', $merchant->merchant_id)->where('status', 'active')->first();
                    if ($merchantApiHitLimit) {
                        $checkLimit = $this->countCallbackHits($merchantApiHitLimit, $request->acc_id);
                        if ($checkLimit) {
                            Log::error($checkLimit, ['payload' => $request->all(), 'response' => $responseData]);
                            ApiLoggerService::logEvent(
                                '/v1/payout-request',
                                $checkLimit,
                                'Payout Callback failed for merchant. URL: ' . ($merchant->callback_url ?? "N/A"),
                                $logPayload,
                                $responseData,
                                'error'
                            );
                        } else {
                            Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->callback_url, $responseData);
                            ApiLoggerService::logEvent(
                                '/v1/payout-request',
                                'Callback attempted',
                                'Payout callback sent for merchant. URL: ' . ($merchant->callback_url ?? "N/A"),
                                $logPayload,
                                $responseData,
                                'info'
                            );
                        }
                    } else {
                        Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->callback_url, $responseData);
                        ApiLoggerService::logEvent(
                            '/v1/payout-request',
                            'Callback attempted',
                            'Payout callback sent for merchant. URL: ' . ($merchant->callback_url ?? "N/A"),
                            $logPayload,
                            $responseData,
                            'info'
                        );
                    }
            } else {
                Log::error("Invalid callback URL for merchant", ["url" => $merchant->callback_url]);
            }
            return response()->json($responseData, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            Log::error('Paykuber Payout2 Exception: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error!'], 500);
        }
    }

    private function getAgentCommission($agentId, $amt, $trxType)
    {
        $agent = Agent::find($agentId);
        $commission = 0;
        if ($agent) {
            if ($trxType === 'credit') {
                if ($amt > 0 && $amt < $agent->payin_switch_amount) {
                    if ($agent->payin_commission_type_below === 'flat') {
                        $commission = $agent->payin_commission_below;
                    } elseif ($agent->payin_commission_type_below === 'percent') {
                        $commission = ($amt * $agent->payin_commission_below) / 100.00;
                    }
                } elseif ($amt >= $agent->payin_switch_amount) {
                    if ($agent->payin_commission_type_above === 'flat') {
                        $commission = $agent->payin_commission_above;
                    } elseif ($agent->payin_commission_type_above === 'percent') {
                        $commission = ($amt * $agent->payin_commission_above) / 100.00;
                    }
                } else {
                    $commission = 0;
                }
            } elseif ($trxType === 'debit') {
                if ($amt > 0 && $amt < $agent->payout_switch_amount) {
                    if ($agent->payout_commission_type_below === 'flat') {
                        $commission = $agent->payout_commission_below;
                    } elseif ($agent->payout_commission_type_below === 'percent') {
                        $commission = ($amt * $agent->payout_commission_below) / 100.00;
                    }
                } elseif ($amt >= $agent->payout_switch_amount) {
                    if ($agent->payout_commission_type_above === 'flat') {
                        $commission = $agent->payout_commission_above;
                    } elseif ($agent->payout_commission_type_above === 'percent') {
                        $commission = ($amt * $agent->payout_commission_above) / 100.00;
                    }
                } else {
                    $commission = 0;
                }
            }
        }
        return $commission;
    }

    private function getPaykuberCommission($ptGateway, $amt, $trxType)
    {
        $charge = 0;
        if ($trxType === 'credit') {
            if ($amt > 0 && $amt < $ptGateway->payin_switch_amount) {
                if ($ptGateway->payin_charges_type_below === 'flat') {
                    $charge = $ptGateway->payin_charges_below;
                } elseif ($ptGateway->payin_charges_type_below === 'percent') {
                    $charge = ($amt * $ptGateway->payin_charges_below) / 100.00;
                } else {
                    $charge = 0;
                }
            } elseif ($amt >= $ptGateway->payin_switch_amount) {
                if ($ptGateway->payin_charges_type_above === 'flat') {
                    $charge = $ptGateway->payin_charges_above;
                } elseif ($ptGateway->payin_charges_type_above === 'percent') {
                    $charge = ($amt * $ptGateway->payin_charges_above) / 100.00;
                } else {
                    $charge = 0;
                }
            } else {
                $charge = 0;
            }
        } elseif ($trxType === 'debit') {
            if ($amt > 0 && $amt < $ptGateway->payout_switch_amount) {
                if ($ptGateway->payout_charges_type_below === 'flat') {
                    $charge = $ptGateway->payout_charges_below;
                } elseif ($ptGateway->payout_charges_type_below === 'percent') {
                    $charge = ($amt * $ptGateway->payout_charges_below) / 100.00;
                } else {
                    $charge = 0;
                }
            } elseif ($amt >= $ptGateway->payout_switch_amount) {
                if ($ptGateway->payout_charges_type_above === 'flat') {
                    $charge = $ptGateway->payout_charges_above;
                } elseif ($ptGateway->payout_charges_type_above === 'percent') {
                    $charge = ($amt * $ptGateway->payout_charges_above) / 100.00;
                } else {
                    $charge = 0;
                }
            } else {
                $charge = 0;
            }
        }
        return $charge;
    }

    private function getUtr($data)
    {
        if (isset($data['data']['utr'])) {
            return $data['data']['utr'];
        }
        return null;
    }

    private function checkOverallHits(MerchantApiHitLimit $apiHits)
    {
        $overallHits = $apiHits->overall_hit_limit;
        $usedPayinHits = $apiHits->payin_hits;
        $usedPayoutHits = $apiHits->payout_hits;
        $usedBalanceCheckHits = $apiHits->balance_check_hits;
        $usedTransactionCheckHits = $apiHits->transaction_check_hits;
        $usedWebhookHits = $apiHits->webhook_hits;
        $usedCallbackHits = $apiHits->callback_hits;
        $usedOverallHits = $usedPayinHits + $usedPayoutHits + $usedBalanceCheckHits + $usedTransactionCheckHits + $usedWebhookHits + $usedCallbackHits;
        return $usedOverallHits >= $overallHits;
    }

    private function countCallbackHits(MerchantApiHitLimit $apiHits, $accid)
    {
        $limit = $apiHits->callback_hits_limit;
        $time = $apiHits->callback_hits_time;

        // Count Callback Hits in the last 'callback_hits_time' minutes
        $previousHits = ApiLog::where('event', 'Callback attempted')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();

        // If hits exceed limit, block the request
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Callback hit limit reached'], 429);
        }

        // Increment Callback Hits
        $apiHits->increment('callback_hits');
        return null;
    }

    private function countWebhookkHits(MerchantApiHitLimit $apiHits, $accid)
    {
        $limit = $apiHits->webhook_hits_limit;
        $time = $apiHits->webhook_hits_time;

        // Count Webhook Hits in the last 'webhook_hits_time' minutes
        $previousHits = ApiLog::where('event', 'Webhook attempted')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();

        // If hits exceed limit, block the request
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Webhook hit limit reached'], 429);
        }

        // Increment Webhook Hits
        $apiHits->increment('webhook_hits');
        return null;
    }

    public function payRequest(Request $request) // Tested & Ready
    {
        ApiLoggerService::logEvent(
            '/v1/pay-request',
            'Payin Hit',
            'User is initiating a payin request.',
            $request->all(),
            null,
            'info'
        );
        $admin = Admin::find(1);
        if(!$admin){
            return response()->json(['message'=>'Something went wrong! Please contact admin.'],500);
        }
        $rules = [
            'acc_id' => 'required|exists:merchant_infos,acc_id',
            'amount' => "required|numeric|min:$admin->payin_min_amt|max:$admin->payin_max_amt",
            'currency' => 'required|string|in:INR,USD,EUR',
            'order_id' => 'required|string|unique:transactions,order_id',
            'sub_pay_mode' => 'required|string|in:qr_ap,intent',
            'merchant_id' => 'required|string',
            'vpa' => 'required|string|regex:/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/',
            'cust_name' => 'required|string',
            'cust_email' => 'required|email',
            'callback_url' => 'required|url',
            'redirect_url' => 'required|url'
        ];
        $messages = [
            'acc_id.required' => 'Account ID is required.',
            'acc_id.exists' => 'Invalid account id.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Minimum Amount must be '.$admin->payin_min_amt.'.',
            'amount.max' => 'Maximum Amount must be '.$admin->payin_max_amt.'.',
            'currency.required' => 'Currency is required.',
            'currency.in' => 'Invalid currency type.',
            'order_id.required' => 'Order ID is required.',
            'order_id.unique' => 'Order ID must be unique.',
            'sub_pay_mode.required' => 'Sub Pay Mode is required.',
            'merchant_id.required' => 'Merchant ID is required.',
            'callback_url.required' => 'Callback URL is required.',
            'callback_url.url' => 'Callback URL must be a valid URL.',
            'redirect_url.required' => 'Redirect URL is required.',
            'redirect_url.url' => 'Redirect URL must be a valid URL.',
            'cust_name.required' => 'Customer Name is required.',
            'cust_email.required' => 'Customer Email is required.',
            'cust_email.email' => 'Customer Email must be a valid email address.',
            'vpa.required' => 'VPA is required.',
            'vpa.regex' => 'Invalid VPA format.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $apiToken = $request->attributes->get('api_token');
            $merchant = MerchantInfo::where('acc_id', $request->acc_id)->first();
            if (!$merchant || $merchant->merchant_status !== "Active") {
                return response()->json(['message' => 'Account is locked or customer does not exist! Please contact admin.'], 403);
            }
            $wallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
            if (!$wallet) {
                return response()->json(['message' => 'Internal server error.'], 500);
            }
            $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->where('status', 'active')->first();
            if (!$merchantGateway) {
                return response()->json(['message' => 'You are not allowed to make requests.'], 403);
            }
            // Updated on 20-03-2025 by Ketan Gupta Start
            $ptGateway = PaymentGateway::find($merchantGateway->payin_gateway_id);
            if (!$ptGateway) {
                return response()->json(['message' => 'Payment gateway configuration error! Please contact support.'], 403);
            }
            switch ($ptGateway->gateway_type) {
                case 'payin':
                case 'both':
                    break;
                default:
                    return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
            }
            // Updated on 20-03-2025 by Ketan Gupta End
            // Payload (callback data)
            $data = json_encode($request->all());
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($data, $secret);
            $logPayload = [
                'payload' => $request->all(),
                'signature' => $signature
            ];
            // Updated on 20-03-2025 by Ketan Gupta Start
            $amount = (float) $request->amount;
            $charges = 0.00;
            $gatewayCharge = 0.00;
            $gatewayChargeType = null;
            if ($amount >= 0 && $amount < $merchantGateway->payin_switch_amount) {
                $gatewayCharge = $merchantGateway->payin_charge ?? 0;
                $gatewayChargeType = $merchantGateway->payin_charge_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $charges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $charges = ($amount * $gatewayCharge) / 100.00;
                } else {
                    $charges = 0.0;
                }
            } elseif ($amount >= $merchantGateway->payin_switch_amount) {
                $gatewayCharge = $merchantGateway->payin_charge2 ?? 0;
                $gatewayChargeType = $merchantGateway->payin_charge2_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $charges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $charges = ($amount * $gatewayCharge) / 100.00;
                } else {
                    $charges = 0.0;
                }
            } else {
                $charges = 0.0;
            }
            $gst = 0.00;
            if($merchantGateway->gst_enabled === "yes"){
                $gst = ($charges * (float)$merchantGateway->gst_percentage) / 100.00;
            }
            $charges = $charges + $gst;
            $merchantWallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
            if ($merchantWallet) {
                $penality = $merchant->payin_hit_charge ?? 0.00;
                $failedHitsLimit = (($merchant->payin_failed_hits ?? 1) <= 0) ? 1 : $merchant->payin_failed_hits;
                $lastTransactions = WalletTransaction::where('type', 'credit')
                    ->where('merchant_id', $merchant->merchant_id)
                    ->orderBy('created_at', 'DESC')
                    ->limit($failedHitsLimit)
                    ->get();
                if ($lastTransactions->count() >= $failedHitsLimit) {
                    foreach ($lastTransactions as $trxn) {
                        if ($trxn->status === 'completed' || $trxn->status === 'successful') {
                            $penality = 0.00;
                            break;
                        }
                    }
                    if ($penality > 0) {
                        $merchantWallet->update([
                            'balance' => $merchantWallet->balance - $penality
                        ]);
                    }
                }
            }
            // Updated on 20-03-2025 by Ketan Gupta End
            $transaction = Transaction::create([
                'order_id' => $request->order_id,
                'gateway' => 'Paykuber',
                'api_token' => is_object($apiToken) ? $apiToken->token : $apiToken,
                'request_data' => $logPayload
            ]);
            $payload = [
                "amount" => $amount,
                "currency" => $request->currency,
                "order_id" => $request->order_id,
                "sub_pay_mode" => $request->sub_pay_mode,
                "merchant_id" => $request->merchant_id,
                "vpa" => $request->vpa,
                "cust_name" => $request->cust_name,
                "cust_email" => $request->cust_email,
                "callback_url" => $request->callback_url,
                "redirect_url" => $request->redirect_url
            ];
            $response = $this->makePaykuberRequest($payload, 'https://api1.paykuber.com/v2/pay-request', $apiToken);
            $responseData = $response->json();
            // Ensure $responseData is not empty
            if (empty($responseData) || !$response->successful() || isset($responseData['error'])) {
                // Updated on 20-03-2025 by Ketan Gupta Start
                WalletTransaction::create([
                    'merchant_id' => $merchant->merchant_id,
                    'amount' => $amount,
                    'charge' => ($charges - $gst),
                    'gst' => $gst,
                    'gst_inc_charge' => $charges,
                    'type' => 'credit',
                    'transaction_id' => $request->order_id,
                    'remarks' => 'Payin request initiated',
                    'utr' => null,
                    'acc_no' => $request->account_number ?? null,
                    'ifsc' => $request->bank_ifsc ?? null,
                    'beneficiary_name' => $request->bene_name ?? null,
                    'current_balance' => $wallet->balance,
                    'current_pending_balance' => $wallet->pending_balance,
                    'current_hold_balance' => $wallet->roling_balance,
                    'settlement_status' => 'not settled',
                    'pt_gateway_charges' => 0,
                    'pt_agent_commission' => 0,
                    'status' => 'expired',
                    'visibility' => 'visible'
                ]);
                $transaction->update([
                    'status' => 'expired'
                ]);
                // Updated on 20-03-2025 by Ketan Gupta End
                $customeResponse = [
                    "status" => "Success",
                    "data" => [
                        "order_id" => $request->order_id,
                        "status" => "expired",
                        "amount" => $amount,
                        "txn_id" => null,
                        "qr_string" => null,
                        "qr_code" => null,
                    ]
                ];
                Log::error("Empty or not successful response received from Paykuber", ["payload" => $payload, "response" => $response->body()]);
                ApiLoggerService::logEvent(
                    '/v1/pay-request',
                    'Paykuber Payin Response',
                    'Empty or not successful response received from Paykuber',
                    $logPayload,
                    $response->body(),
                    'error'
                );
                return response()->json($customeResponse, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
            }
            ApiLoggerService::logEvent(
                '/v1/pay-request',
                'Paykuber Payin Response',
                'Received response from the Paykuber gateway.',
                $logPayload,
                $response->body(),
                $response->successful() ? 'info' : 'error'
            );
            $transaction->update([
                'response_data' => $responseData
            ]);
            // Updated on 20-03-2025 by Ketan Gupta Start
            return response()->json($charges);
            WalletTransaction::create([
                'merchant_id' => $merchant->merchant_id,
                'amount' => $amount,
                'charge' => $charges,
                'gst' => $gst,
                'gst_inc_charge' => $charges,
                'type' => 'credit',
                'transaction_id' => $request->order_id,
                'remarks' => 'Payin request initiated',
                'utr' => null,
                'acc_no' => $request->account_number ?? null,
                'ifsc' => $request->bank_ifsc ?? null,
                'beneficiary_name' => $request->bene_name ?? null,
                'current_balance' => $wallet->balance,
                'current_pending_balance' => $wallet->pending_balance,
                'current_hold_balance' => $wallet->roling_balance,
                'settlement_status' => 'not settled',
                'pt_gateway_charges' => 0,
                'pt_agent_commission' => 0,
                'status' => 'initiated',
                'visibility' => 'visible'
            ]);
            // Updated on 20-03-2025 by Ketan Gupta End
            if (isset($responseData['error'])) {
                return response()->json($responseData, 400);
            } else {
                if (filter_var($merchant->webhook_url, FILTER_VALIDATE_URL)) {
                    $merchantApiHitLimit = MerchantApiHitLimit::where('merchant_id', $merchant->merchant_id)->where('status', 'active')->first();
                    if ($merchantApiHitLimit) {
                        $checkLimit = $this->countWebhookkHits($merchantApiHitLimit, $request->acc_id);
                        if ($checkLimit) {
                            Log::error($checkLimit, ['payload' => $request->all(), 'response' => $responseData]);
                            ApiLoggerService::logEvent(
                                '/v1/pay-request',
                                $checkLimit,
                                'Payin Webhook failed for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                                $logPayload,
                                $response->body(),
                                'error'
                            );
                        } else {
                            Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->webhook_url, $responseData);
                            ApiLoggerService::logEvent(
                                '/v1/pay-request',
                                'Webhook attempted',
                                'Payin Webhook sent for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                                $logPayload,
                                $responseData,
                                'info'
                            );
                        }
                    } else {
                        Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->webhook_url, $responseData);
                        ApiLoggerService::logEvent(
                            '/v1/pay-request',
                            'Webhook attempted',
                            'Payin Webhook sent for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                            $logPayload,
                            $responseData,
                            'info'
                        );
                    }
                } else {
                    Log::error("Invalid webhook URL for merchant", ["url" => $merchant->webhook_url]);
                    ApiLoggerService::logEvent(
                        '/v1/pay-request',
                        'Payin Webhook Error',
                        'Invalid webhook URL for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                        $logPayload,
                        $responseData,
                        'error'
                    );
                }
                $paymentUrl = $responseData['data']['paymentLink'];
                parse_str(parse_url($paymentUrl, PHP_URL_QUERY), $queryParams);
                $uriId = $queryParams['id'] ?? null;
                $handmadeResponse = [
                    "status" => $responseData['status'],
                    "data" => [
                        "order_id" => $responseData['data']['order_id'],
                        "status" => $responseData['data']['status'],
                        "amount" => $responseData['data']['amount'],
                        "txn_id" => $responseData['data']['txn_id'],
                        "qr_string" => $responseData['data']['qr_string'],
                        "qr_code" => $responseData['data']['qr_code'],
                        "paymentLink" => $uriId ? url('/pay-' . $uriId) : null
                    ]
                ];
                return response()->json($handmadeResponse, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
            }
        } catch (Exception $e) {
            Log::error('Paykuber payRequest exception: ' . $e->getMessage());
            ApiLoggerService::logEvent(
                '/v1/pay-request',
                'Paykuber payRequest Exception',
                $e->getMessage(),
                null,
                null,
                'exception'
            );
            try {
                $transaction->update([
                    'status' => 'expired'
                ]);
                $walletTransaction = WalletTransaction::where('transaction_id', $transaction->order_id)->first();
                if ($walletTransaction) {
                    $walletTransaction->update([
                        'status' => 'expired'
                    ]);
                } else {
                    WalletTransaction::create([
                        'merchant_id' => $merchant->merchant_id,
                        'amount' => $amount,
                        'charge' => ($charges - $gst),
                        'gst' => $gst,
                        'gst_inc_charge' => $charges,
                        'type' => 'credit',
                        'transaction_id' => $request->order_id,
                        'remarks' => 'Payin request expired',
                        'utr' => null,
                        'acc_no' => $request->account_number ?? null,
                        'ifsc' => $request->bank_ifsc ?? null,
                        'beneficiary_name' => $request->bene_name ?? null,
                        'current_balance' => $wallet->balance,
                        'current_pending_balance' => $wallet->pending_balance,
                        'current_hold_balance' => $wallet->roling_balance,
                        'settlement_status' => 'not settled',
                        'pt_gateway_charges' => 0,
                        'pt_agent_commission' => 0,
                        'status' => 'expired',
                        'visibility' => 'visible'
                    ]);
                }
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
            }
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }

    public function seamlessTxnStatus(Request $request) // New Logic Updated, Tested and Working as per 07-03-2025
    {
        $rules = [
            'acc_id' => 'required|exists:merchant_infos,acc_id',
            'type' => 'required|string|in:payin,payout',
            'merchant_id' => 'required|string',
            'order_id' => 'required|string|exists:wallet_transactions,transaction_id',
        ];
        $messages = [
            'acc_id.required' => 'Account ID is required.',
            'acc_id.exists' => 'Invalid account id.',
            'type.required' => 'Type is required.',
            'type.in' => "Type must be either 'payin' or 'payout'.",
            'merchant_id.required' => 'Merchant ID is required.',
            'order_id.required' => 'Order ID is required.',
            'order_id.string' => 'Invalid order id.',
            'order_id.exists' => 'Transaction not found.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }
        try {
            $apiToken = $request->attributes->get('api_token');
            $merchant = MerchantInfo::where('acc_id', $request->acc_id)->first();
            if (!$merchant || $merchant->merchant_status !== "Active") {
                return response()->json(['message' => 'Account is locked or customer does not exist! Please contact admin.'], 403);
            }
            $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->where('status', 'active')->first();
            if (!$merchantGateway) {
                return response()->json(['message' => 'You are not allowed to make requests.'], 403);
            }
            $orderId = $request->order_id;
            $walletTransactionCheck = WalletTransaction::where('merchant_id', $merchant->merchant_id)
                ->where('transaction_id', $orderId)
                ->where('visibility', 'visible')
                ->first();
            if (!$walletTransactionCheck) {
                return response()->json('Transaction not found', 200);
            }
            // Payload (callback data)
            $data = json_encode($request->all());
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($data, $secret);

            $logPayload = [
                'payload' => $request->all(),
                'signature' => $signature
            ];

            $payload = [
                'type' => $request->type,
                'merchant_id' => $request->merchant_id,
                'order_id' => $request->order_id
            ];
            $response = $this->makePaykuberRequest($payload, 'https://api1.paykuber.com/api/seamless/txnStatus', $apiToken);
            $responseData = $response->json();
            ApiLoggerService::logEvent(
                '/v1/seamless/txn-status',
                'Transaction Status',
                'Transaction status received for gateway Paykuber.',
                $logPayload,
                $response->body(),
                $response->successful() ? 'info' : 'error'
            );
            if (isset($responseData['error'])) {
                $walletTransaction = WalletTransaction::where('merchant_id', $merchant->merchant_id)
                    ->where('transaction_id', $orderId)
                    ->first();
                if ($walletTransaction) {
                    $customResponse = [
                        "status" => "SUCCESS",
                        "data" => [
                            "order_id" => $walletTransaction->transaction_id,
                            "status" => $walletTransaction->status,
                            "amount" => $walletTransaction->amount,
                        ]
                    ];
                    return response()->json($customResponse, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
                } else {
                    return response()->json($responseData, 400);
                }
            } else {
                if (!isset($responseData['data']['status'])) {
                    // Code added on 18-03-2025 by Ketan
                    $walletTransaction = WalletTransaction::where('merchant_id', $merchant->merchant_id)
                        ->where('transaction_id', $orderId)
                        ->first();
                    if ($walletTransaction) {
                        $customResponse = [
                            "status" => "SUCCESS",
                            "data" => [
                                "order_id" => $walletTransaction->transaction_id,
                                "status" => $walletTransaction->status,
                                "amount" => $walletTransaction->amount,
                            ]
                        ];
                        return response()->json($customResponse, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
                    } else {
                        return response()->json("Transaction not found!");
                    }
                }
                $incomingStatus = strtolower($responseData['data']['status']);
                $mappedStatus = match ($incomingStatus) {
                    'completed' => 'successful',
                    'processing' => 'processing',
                    'initiated' => 'initiated',
                    'failed' => 'failed',
                    'expired' => 'expired',
                    'queued' => 'queued',
                    'pending' => 'pending',
                    default => 'expired'
                };
                $transaction = Transaction::where('trx_type', $request->type)
                    ->where('status', '!=', 'successful')
                    ->where('order_id', $orderId)
                    ->first();
                // Update status if any status change found
                if ($transaction) {
                    if ($transaction->status != $mappedStatus) {
                        $transaction->update([
                            'status' => $mappedStatus,
                            'response_data' => $responseData,
                        ]);
                        ApiLoggerService::logEvent(
                            '/v1/seamless/txn-status',
                            'Transaction Status',
                            "Transaction status updated for order id {$request->order_id}.",
                            $logPayload,
                            $responseData,
                            $mappedStatus === 'successful' ? 'info' : 'error'
                        );
                    }
                }
                $walletTransaction = WalletTransaction::where('merchant_id', $merchant->merchant_id)
                    ->where('transaction_id', $orderId)
                    ->where(function ($query) {
                        $query->where("status", "!=", "completed")
                            ->where("status", "!=", "successful");
                    })
                    ->first();
                $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->first();
                $merchantWallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
                $amount = (float)$responseData['data']['amount'];
                if ($walletTransaction && $merchantGateway && $merchantWallet) {
                    if ($walletTransaction->type == "debit" && ($mappedStatus === "failed" || $mappedStatus === "expired")) {
                        $adjustableAmount = $walletTransaction->amount + $walletTransaction->charge;
                        $oldBalance = $merchantWallet->balance;
                        $oldRolingBalance = $merchantWallet->roling_balance;
                        if ($oldRolingBalance < $adjustableAmount) {
                            ApiLoggerService::logEvent(
                                '/v1/seamless/txn-status',
                                'Transaction Status',
                                "Old rolling balance is less than adjustable amount for transaction id {$request->order_id}. Old rolling balance: {$oldRolingBalance}, Adjustable amount: {$adjustableAmount} | Current balance: {$oldBalance} | Merchant ID: {$merchant->merchant_id}",
                                $logPayload,
                                $responseData,
                                'error'
                            );
                            Log::error("Old rolling balance is less than adjustable amount for transaction id {$request->order_id}. Old rolling balance: {$oldRolingBalance}, Adjustable amount: {$adjustableAmount} | Current balance: {$oldBalance} | Merchant ID: {$merchant->merchant_id}");
                            return response()->json($responseData, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
                        }
                        $merchantWallet->update([
                            'balance' => $oldBalance + $adjustableAmount,
                            'roling_balance' => $oldRolingBalance - $adjustableAmount
                        ]);
                    }
                    // **Only update balance when status is 'successful'**
                    if ($mappedStatus !== 'successful') {
                        if ($walletTransaction->status != $mappedStatus) {
                            $walletTransaction->update([
                                'status' => $mappedStatus
                            ]);
                            ApiLoggerService::logEvent(
                                '/v1/seamless/txn-status',
                                'Transaction Status',
                                "Wallet Transaction status updated for transaction id {$request->order_id}.",
                                $logPayload,
                                $responseData,
                                $mappedStatus === 'successful' ? 'info' : 'error'
                            );
                        }
                        return response()->json($responseData, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
                    }
                    $ptGatewayId = $walletTransaction->type == 'credit' ? $merchantGateway->payin_gateway_id : $merchantGateway->payout_gateway_id;
                    $ptGateway = PaymentGateway::find($ptGatewayId);
                    if (!$ptGateway) {
                        ApiLoggerService::logEvent(
                            '/v1/seamless/txn-status',
                            'Payment Gateway Error',
                            "Payment Gateway configuration error for transaction id {$request->order_id}. Merchant wallet update not successful.",
                            $logPayload,
                            $responseData,
                            'error'
                        );
                        return response()->json([
                            'message' => 'Gateway configuration error! Please contact support.',
                            'status' => 'error'
                        ], 403);
                    }
                    if ($walletTransaction->type == 'credit') {
                        switch ($ptGateway->gateway_type) {
                            case 'payin':
                            case 'both':
                                break;
                            default:
                                ApiLoggerService::logEvent(
                                    '/v1/seamless/txn-status',
                                    'Payment Gateway Error',
                                    "Payment gateway type error for transaction id {$request->order_id}. Merchant wallet update not successful.",
                                    $logPayload,
                                    $responseData,
                                    'error'
                                );
                                return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
                        }
                    } elseif ($walletTransaction->type == 'debit') {
                        switch ($ptGateway->gateway_type) {
                            case 'payout':
                            case 'both':
                                break;
                            default:
                                ApiLoggerService::logEvent(
                                    '/v1/seamless/txn-status',
                                    'Payment Gateway Error',
                                    "Payment gateway type error for transaction id {$request->order_id}. Merchant wallet update not successful.",
                                    $logPayload,
                                    $responseData,
                                    'error'
                                );
                                return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
                        }
                    } else {
                        return response()->json(['message' => 'Transaction type error! Please contact support.'], 403);
                    }
                    $charges = 0.00;
                    $gatewayCharge = 0.00;
                    $gatewayChargeType = null;
                    if ($walletTransaction->type == 'credit') {
                        if ($amount >= 0 && $amount < $merchantGateway->payin_switch_amount) {
                            $gatewayCharge = $merchantGateway->payin_charge ?? 0;
                            $gatewayChargeType = $merchantGateway->payin_charge_type ?? "flat";
                            if ($gatewayChargeType === "flat") {
                                $charges = $gatewayCharge;
                            } elseif ($gatewayChargeType === "percent") {
                                $charges = ($amount * $gatewayCharge) / 100.00;
                            } else {
                                $charges = 0.0;
                            }
                        } elseif ($amount >= $merchantGateway->payin_switch_amount) {
                            $gatewayCharge = $merchantGateway->payin_charge2 ?? 0;
                            $gatewayChargeType = $merchantGateway->payin_charge2_type ?? "flat";
                            if ($gatewayChargeType === "flat") {
                                $charges = $gatewayCharge;
                            } elseif ($gatewayChargeType === "percent") {
                                $charges = ($amount * $gatewayCharge) / 100.00;
                            } else {
                                $charges = 0.0;
                            }
                        } else {
                            $charges = 0.0;
                        }
                    } elseif ($walletTransaction->type == 'debit') {
                        if ($amount >= 0 && $amount < $merchantGateway->payout_switch_amount) {
                            $gatewayCharge = $merchantGateway->payout_charge ?? 0;
                            $gatewayChargeType = $merchantGateway->payout_charge_type ?? "flat";
                            if ($gatewayChargeType === "flat") {
                                $charges = $gatewayCharge;
                            } elseif ($gatewayChargeType === "percent") {
                                $charges = ($amount * $gatewayCharge) / 100.00;
                            } else {
                                $charges = 0.0;
                            }
                        } elseif ($amount >= $merchantGateway->payout_switch_amount) {
                            $gatewayCharge = $merchantGateway->payout_charge2 ?? 0;
                            $gatewayChargeType = $merchantGateway->payout_charge2_type ?? "flat";
                            if ($gatewayChargeType === "flat") {
                                $charges = $gatewayCharge;
                            } elseif ($gatewayChargeType === "percent") {
                                $charges = ($amount * $gatewayCharge) / 100.00;
                            } else {
                                $charges = 0.0;
                            }
                        } else {
                            $charges = 0.0;
                        }
                    } else {
                        return response()->json($responseData, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
                    }
                    // Code added on 20-03-2025 by Ketan Start
                    $agentCommission = $this->getAgentCommission($merchant->agent_id ?? 0, $amount, $walletTransaction->type);
                    $paykuberCommission = $this->getPaykuberCommission($ptGateway, $amount, $walletTransaction->type);
                    // **Update balance only when status is successful**
                    $walletTransaction->update([
                        'utr' => $this->getUtr($responseData),
                        'charge' => $charges,
                        'amount' => $amount,
                        'status' => $mappedStatus,
                        'pt_agent_commission' => $agentCommission,
                        'pt_gateway_charges' => $paykuberCommission
                    ]);
                    // Code added on 20-03-2025 by Ketan End
                    if ($mappedStatus === 'successful') {
                        if ($walletTransaction->type == 'credit') {
                            $newBalance = $amount - $charges;
                            // Rolling Reserve Calculation
                            $splittedAmount = $this->rollingReserve(
                                $walletTransaction->merchant_id,
                                $walletTransaction->transaction_id,
                                $amount,
                                $newBalance,
                                $merchant->rolling_charge
                            );
                            $oldPendingAmt = (float)$merchantWallet->pending_balance;
                            $oldRollingAmt = (float)$merchantWallet->roling_balance;
                            $merchantWallet->update([
                                'pending_balance' => $oldPendingAmt + (float)$splittedAmount['pending_amount'],
                                'roling_balance' => $oldRollingAmt + (float)$splittedAmount['rolling_amount'],
                            ]);
                            // Code added on 20-03-2025 by Ketan Start
                            $walletTransaction->update([
                                'current_balance' => $merchantWallet->balance,
                                'current_pending_balance' => $merchantWallet->pending_balance,
                                'current_hold_balance' => $merchantWallet->roling_balance
                            ]);
                            // Code added on 20-03-2025 by Ketan End
                            $newBalanceLog = [
                                'old_pending_amount' => $oldPendingAmt,
                                'old_roling_amount' => $oldRollingAmt,
                                'new_pending_amount' => $oldPendingAmt + (float)$splittedAmount['pending_amount'],
                                'new_roling_amount' => $oldRollingAmt + (float)$splittedAmount['rolling_amount'],
                            ];
                            Log::info("Merchant wallet updated for transaction id {$request->order_id}", $newBalanceLog);
                            ApiLoggerService::logEvent(
                                '/v1/seamless/txn-status',
                                'Payin New Balance Update Log',
                                "Payin New Balance Update Log for transaction id {$request->order_id}. Merchant wallet updated successfully.",
                                $logPayload,
                                ["response" => $responseData, "new_balance_update_log" => $newBalanceLog],
                                'info'
                            );
                        }
                        if ($walletTransaction->type == 'debit') {
                            $newBalance = $amount + $charges;
                            $oldRolingBalance = (float)$merchantWallet->roling_balance;
                            $merchantWallet->update([
                                'roling_balance' => $oldRolingBalance - $newBalance,
                            ]);
                            // Code added on 20-03-2025 by Ketan Start
                            $walletTransaction->update([
                                'current_balance' => $merchantWallet->balance,
                                'current_pending_balance' => $merchantWallet->pending_balance,
                                'current_hold_balance' => $merchantWallet->roling_balance
                            ]);
                            // Code added on 20-03-2025 by Ketan End
                            $newBalanceLog = [
                                'old_roling_balance' => $oldRolingBalance,
                                'new_roling_balance' => $oldRolingBalance - $newBalance
                            ];
                            Log::info("Merchant wallet updated for transaction id {$request->order_id}", $newBalanceLog);
                            ApiLoggerService::logEvent(
                                '/v1/seamless/txn-status',
                                'Payout New Balance Update Log',
                                "Payout New Balance Update Log for transaction id {$request->order_id}. Merchant wallet updated successfully.",
                                $logPayload,
                                ["response" => $responseData, "new_balance_update_log" => $newBalanceLog],
                                'info'
                            );
                        }
                        // Send Callback Notification if URL exists
                        $url = $walletTransaction->type === "debit" ? ($merchant->callback_url ?? null) : ($merchant->webhook_url ?? null);
                        // Payload (callback data)
                        $data = $transaction->request_data;
                        $secret = $merchantGateway->salt_key; // Salt Key from database
                        $signature = $this->encryptData(json_encode($data), $secret);
                        if ($url) {
                            try {
                                if ($walletTransaction->type === "debit" && strtolower($responseData['data']['status']) === "failed") {
                                    $newpayload = ["message" => "Payout failed!"];
                                    $merchantApiHitLimit = MerchantApiHitLimit::where('merchant_id', $merchant->merchant_id)->where('status', 'active')->first();
                                    if ($merchantApiHitLimit) {
                                        $checkLimit = $this->countCallbackHits($merchantApiHitLimit, $request->acc_id);
                                        if ($checkLimit) {
                                            Log::error($checkLimit, ['payload' => $request->all(), 'response' => $responseData]);
                                            ApiLoggerService::logEvent(
                                                '/v1/seamless/txn-status',
                                                $checkLimit,
                                                "Merchant Callback Failed for transaction id {$request->order_id} on URL: " . $url,
                                                $logPayload,
                                                $responseData,
                                                'error'
                                            );
                                        } else {
                                            $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $newpayload);
                                            if (!$response->successful()) {
                                                Log::error("Merchant Callback Failed", ['response' => $response->body()]);
                                                ApiLoggerService::logEvent(
                                                    '/v1/seamless/txn-status',
                                                    'Merchant Callback Failed',
                                                    "Merchant Callback Failed for transaction id {$request->order_id} on URL: " . $url,
                                                    $logPayload,
                                                    $responseData,
                                                    'error'
                                                );
                                            } else {
                                                Log::error("Merchant Callback Sent", ['response' => $response->body()]);
                                                ApiLoggerService::logEvent(
                                                    '/v1/seamless/txn-status',
                                                    'Merchant Callback Sent',
                                                    "Merchant Callback Sent for transaction id {$request->order_id} on URL: " . $url,
                                                    $logPayload,
                                                    $responseData,
                                                    'info'
                                                );
                                            }
                                        }
                                    } else {
                                        $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $newpayload);
                                        if (!$response->successful()) {
                                            Log::error("Merchant Callback Failed", ['response' => $response->body()]);
                                            ApiLoggerService::logEvent(
                                                '/v1/seamless/txn-status',
                                                'Merchant Callback Failed',
                                                "Merchant Callback Failed for transaction id {$request->order_id} on URL: " . $url,
                                                $logPayload,
                                                $responseData,
                                                'error'
                                            );
                                        } else {
                                            Log::error("Merchant Callback Sent", ['response' => $response->body()]);
                                            ApiLoggerService::logEvent(
                                                '/v1/seamless/txn-status',
                                                'Merchant Callback Sent',
                                                "Merchant Callback Sent for transaction id {$request->order_id} on URL: " . $url,
                                                $logPayload,
                                                $responseData,
                                                'info'
                                            );
                                        }
                                    }
                                } else {
                                    $merchantApiHitLimit = MerchantApiHitLimit::where('merchant_id', $merchant->merchant_id)->where('status', 'active')->first();
                                    if ($merchantApiHitLimit) {
                                        $checkLimit = $this->countWebhookkHits($merchantApiHitLimit, $request->acc_id);
                                        if ($checkLimit) {
                                            Log::error($checkLimit, ['payload' => $request->all(), 'response' => $responseData]);
                                            ApiLoggerService::logEvent(
                                                '/v1/seamless/txn-status',
                                                $checkLimit,
                                                "Merchant Webhook Failed for transaction id {$request->order_id} on URL: " . $url,
                                                $logPayload,
                                                $responseData,
                                                'error'
                                            );
                                        } else {
                                            $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $responseData);
                                            if (!$response->successful()) {
                                                Log::error("Merchant Webhook Failed", ['response' => $response->body()]);
                                                ApiLoggerService::logEvent(
                                                    '/v1/seamless/txn-status',
                                                    'Merchant Webhook Failed',
                                                    "Merchant Webhook Failed for transaction id {$request->order_id} on URL: " . $url,
                                                    $logPayload,
                                                    $responseData,
                                                    'error'
                                                );
                                            } else {
                                                Log::error("Merchant Callback Sent", ['response' => $response->body()]);
                                                ApiLoggerService::logEvent(
                                                    '/v1/seamless/txn-status',
                                                    'Merchant Webhook Sent',
                                                    "Merchant Webhook Sent for transaction id {$request->order_id} on URL: " . $url,
                                                    $logPayload,
                                                    $responseData,
                                                    'info'
                                                );
                                            }
                                        }
                                    } else {
                                        $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $responseData);
                                        if (!$response->successful()) {
                                            Log::error("Merchant Webhook Failed", ['response' => $response->body()]);
                                            ApiLoggerService::logEvent(
                                                '/v1/seamless/txn-status',
                                                'Merchant Webhook Failed',
                                                "Merchant Webhook Failed for transaction id {$request->order_id} on URL: " . $url,
                                                $logPayload,
                                                $responseData,
                                                'error'
                                            );
                                        } else {
                                            Log::error("Merchant Callback Sent", ['response' => $response->body()]);
                                            ApiLoggerService::logEvent(
                                                '/v1/seamless/txn-status',
                                                'Merchant Webhook Sent',
                                                "Merchant Webhook Sent for transaction id {$request->order_id} on URL: " . $url,
                                                $logPayload,
                                                $responseData,
                                                'info'
                                            );
                                        }
                                    }
                                    $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $responseData);
                                    if (!$response->successful()) {
                                        Log::error("Merchant Webhook Failed", ['response' => $response->body()]);
                                        ApiLoggerService::logEvent(
                                            '/v1/seamless/txn-status',
                                            'Merchant Webhook Failed',
                                            "Merchant Webhook Failed for transaction id {$request->order_id} on URL: " . $url,
                                            $logPayload,
                                            $responseData,
                                            'error'
                                        );
                                    } else {
                                        Log::error("Merchant Callback Sent", ['response' => $response->body()]);
                                        ApiLoggerService::logEvent(
                                            '/v1/seamless/txn-status',
                                            'Merchant Webhook Sent',
                                            "Merchant Webhook Sent for transaction id {$request->order_id} on URL: " . $url,
                                            $logPayload,
                                            $responseData,
                                            'info'
                                        );
                                    }
                                }
                            } catch (Exception $e) {
                                Log::error("HTTP Request Exception: " . $e->getMessage());
                            }
                        }
                    }
                }
                return response()->json($responseData, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
            }
        } catch (Exception $e) {
            Log::error('Paykuber seamlessTxnStatus exception: ' . $e);
            ApiLoggerService::logEvent(
                '/v1/seamless/txn-status',
                'Paykuber seamlessTxnStatus Exception',
                $e->getMessage(),
                null,
                null,
                'exception'
            );
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }

    public function payoutRequest(Request $request) // Tested & Ready
    {
        $admin = Admin::find(1);
        if(!$admin){
            return response()->json(['message'=>'Something went wrong! Please contact admin.'],500);
        }
        $rules = [
            'acc_id' => 'required|exists:merchant_infos,acc_id',
            'amount' => "required|numeric|min:$admin->payout_min_amt|max:$admin->payout_max_amt",
            'merchant_id' => 'required|string',
            'currency' => 'required|string|in:INR,USD,EUR',
            'pay_mode' => 'required|string|in:NB,UPI,CARD',
            'sub_pay_mode' => 'required|string|in:IMPS,RTGS,NEFT,CREDIT,DEBIT',
            'bene_name' => 'required|string|max:255',
            'bank_ifsc' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'account_number' => 'required|string|digits_between:9,18',
            'vpa' => 'required|string|regex:/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/',
            'remarks' => 'required|string|max:255',
            'order_id' => 'required|string|unique:transactions,order_id'
        ];
        $messages = [
            'acc_id.required' => 'Account ID is required.',
            'acc_id.exists' => 'Invalid account id.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Minimum Amount must be '.$admin->payout_min_amt.'.',
            'amount.max' => 'Maximum Amount must be '.$admin->payout_max_amt.'.',
            'merchant_id.required' => 'Merchant ID is required.',
            'currency.required' => 'Currency is required.',
            'currency.in' => 'Invalid currency type.',
            'pay_mode.required' => 'Payment Mode is required.',
            'pay_mode.in' => 'Invalid Payment Mode.',
            'sub_pay_mode.required' => 'Sub Pay Mode is required.',
            'sub_pay_mode.in' => 'Invalid Sub Pay Mode.',
            'bene_name.required' => 'Beneficiary Name is required.',
            'bene_name.max' => 'Beneficiary Name must be less than 255 characters.',
            'bank_ifsc.required' => 'IFSC Code is required.',
            'bank_ifsc.regex' => 'Invalid IFSC Code format.',
            'account_number.required' => 'Account Number is required.',
            'account_number.digits_between' => 'Account Number must be between 9 and 18 digits.',
            'vpa.required' => 'VPA is required.',
            'vpa.regex' => 'Invalid VPA format.',
            'remarks.required' => 'Remarks is required.',
            'remarks.max' => 'Remarks must be less than 255 characters.',
            'order_id.required' => 'Order ID is required.',
            'order_id.unique' => 'Order ID must be unique.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }
        try {
            $apiToken = $request->attributes->get('api_token');
            $merchant = MerchantInfo::where('acc_id', $request->acc_id)->first();
            if (!$merchant || $merchant->merchant_status !== "Active") {
                return response()->json(['message' => 'Account is locked or customer does not exist! Please contact admin.'], 402);
            }
            $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->where('status', 'active')->first();
            if (!$merchantGateway) {
                return response()->json(['message' => 'You are not allowed to make requests.'], 402);
            }
            $merchantWallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
            if (!$merchantWallet) {
                return response()->json(['message' => 'Merchant wallet is locked! Please contact admin.'], 402);
            }
            if ($merchant->payout_mode == "processing") {
                return $this->payoutRequest2($request, $merchant, $merchantGateway, $merchantWallet);
            }

            // Updated on 20-03-2025 by Ketan Gupta Start
            $ptGateway = PaymentGateway::find($merchantGateway->payout_gateway_id);
            if (!$ptGateway) {
                return response()->json(['message' => 'Payment gateway configuration error! Please contact support.'], 403);
            }
            switch ($ptGateway->gateway_type) {
                case 'payout':
                case 'both':
                    break;
                default:
                    return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
            }
            // Updated on 20-03-2025 by Ketan Gupta End

            // Payload (callback data)
            $data = json_encode($request->all());
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($data, $secret);
            $logPayload = [
                'payload' => $request->all(),
                'signature' => $signature
            ];

            $amount = (float) $request->amount;
            // Updated on 20-03-2025 by Ketan Gupta Start
            $charges = 0;
            $gatewayCharge = 0;
            $gatewayChargeType = null;

            if ($amount >= 0 && $amount < $merchantGateway->payout_switch_amount) {
                $gatewayCharge = $merchantGateway->payout_charge ?? 0;
                $gatewayChargeType = $merchantGateway->payout_charge_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $charges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $charges = ($amount * $gatewayCharge) / 100.00;
                } else {
                    $charges = 0.0;
                }
            } elseif ($amount >= $merchantGateway->payout_switch_amount) {
                $gatewayCharge = $merchantGateway->payout_charge2 ?? 0;
                $gatewayChargeType = $merchantGateway->payout_charge2_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $charges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $charges = ($amount * $gatewayCharge) / 100.00;
                } else {
                    $charges = 0.0;
                }
            } else {
                $charges = 0.0;
            }
            $gst = 0.00;
            if($merchantGateway->gst_enabled === "yes"){
                $gst = ($charges * (float)$merchantGateway->gst_percentage) / 100.00;
            }
            $charges = $charges + $gst;
            // Updated on 20-03-2025 by Ketan Gupta End
            $penality = $merchant->payout_v_charge ?? 0.00;
            $failedHitsLimit = (($merchant->payout_failed_hits ?? 1) <= 0) ? 1 : $merchant->payout_failed_hits;
            $lastTransactions = WalletTransaction::where('type', 'debit')
                ->where('merchant_id', $merchant->merchant_id)
                ->orderBy('created_at', 'DESC')
                ->limit($failedHitsLimit)
                ->get();
            if ($lastTransactions->count() >= $failedHitsLimit) {
                foreach ($lastTransactions as $trxn) {
                    if ($trxn->status === 'completed' || $trxn->status === 'successful') {
                        $penality = 0.00;
                        break;
                    }
                }
                if ($penality > 0) {
                    $merchantWallet->update([
                        'balance' => $merchantWallet->balance - $penality
                    ]);
                }
            }
            $payableAmount = $amount + $charges;
            if ($merchantWallet->balance < $payableAmount) {
                return response()->json(['message' => 'Insufficient funds! Plese recharge your account and try again.'], 403);
            }
            // $charges = round(($amount * $gatewayCharge) / 100, 2);
            // if ($amount < 500) {
            //     $charges = 11.85;
            // }
            $transaction = Transaction::create([
                'order_id' => $request->order_id,
                'gateway' => 'Paykuber',
                'api_token' => is_object($apiToken) ? $apiToken->token : $apiToken,
                'request_data' => $logPayload,
                'trx_type' => 'payout',
                'status' => 'initiated'
            ]);
            ApiLoggerService::logEvent(
                '/v1/payout-request',
                'Payout Request',
                'Payout request generated for gateway Paykuber.',
                $logPayload
            );

            $payload = [
                'amount' => $request->amount,
                'merchant_id' => $request->merchant_id,
                'currency' => $request->currency,
                'pay_mode' => $request->pay_mode,
                'sub_pay_mode' => $request->sub_pay_mode,
                'bene_name' => $request->bene_name,
                'bank_ifsc' => $request->bank_ifsc,
                'account_number' => $request->account_number,
                'vpa' => $request->vpa,
                'remarks' => $request->remarks,
                'order_id' => $request->order_id,
            ];
            $response = $this->makePaykuberRequest($payload, 'https://api1.paykuber.com/v2/payout-request', $apiToken);
            $responseData = $response->json();
            // Predefined messages
            $errorMessages = [
                'Please try again!(PT)',
                'Too many requests from this IP.(PT)',
                'Oops! Your request was not accepted, please try again.(PT)',
                'Unexpected response from payment gateway, please retry.(PT)',
                'Service is currently unavailable, please try again later.(PT)',
                'Something went wrong, please contact support.(PT)'
            ];
            // Ensure $responseData is not empty
            if (empty($responseData)) {
                Log::error("Empty response received from Paykuber", ["payload" => $payload, "response" => $response->body()]);
                ApiLoggerService::logEvent(
                    '/v1/payout-request',
                    'Paykuber Empty Payout Response',
                    'Empty payout response received from gateway Paykuber.',
                    $logPayload,
                    $response->body(),
                    'error'
                );
                return response()->json(['message' => $errorMessages[array_rand($errorMessages)]], 500);
            }
            ApiLoggerService::logEvent(
                '/v1/payout-request',
                'Paykuber Payout Response',
                'Payout response received from gateway Paykuber.',
                $logPayload,
                $response->body(),
                $response->successful() ? 'info' : 'error'
            );
            $transaction->update([
                'response_data' => $responseData
            ]);
            WalletTransaction::create([
                'merchant_id' => $merchant->merchant_id,
                'amount' => $amount,
                'charge' => ($charges - $gst),
                'gst' => $gst,
                'gst_inc_charge' => $charges,
                'type' => 'debit',
                'transaction_id' => $request->order_id,
                'remarks' => $request->remarks,
                'utr' => $this->getUtr($responseData),
                'acc_no' => $request->account_number ?? null,
                'ifsc' => $request->bank_ifsc ?? null,
                'beneficiary_name' => $request->bene_name ?? null,
                'current_balance' => $merchantWallet->balance,
                'current_pending_balance' => $merchantWallet->pending_balance,
                'current_hold_balance' => $merchantWallet->roling_balance,
                'settlement_status' => null,
                'pt_gateway_charges' => 0,
                'pt_agent_commission' => 0,
                'status' => 'initiated',
                'visibility' => 'visible'
            ]);
            $oldBalance = $merchantWallet->balance;
            $oldRolingBalance = $merchantWallet->roling_balance;
            $merchantWallet->update([
                'balance' => $oldBalance - $payableAmount,
                'roling_balance' => $oldRolingBalance + $payableAmount
            ]);
            ApiLoggerService::logEvent(
                '/v1/payout-request',
                'Payout balance reserved',
                'Old balance: ' . $oldBalance . ', Old roling balance: ' . $oldRolingBalance . ' | New balance: ' . $merchantWallet->balance . ', New roling balance: ' . $merchantWallet->roling_balance,
                $logPayload,
                $responseData,
                'info'
            );
            if (isset($responseData['error'])) {
                return response()->json($responseData, 400);
            } else {
                if ($merchant->callback_url) {
                    $merchantApiHitLimit = MerchantApiHitLimit::where('merchant_id', $merchant->merchant_id)->where('status', 'active')->first();
                    if ($merchantApiHitLimit) {
                        $checkLimit = $this->countCallbackHits($merchantApiHitLimit, $request->acc_id);
                        if ($checkLimit) {
                            Log::error($checkLimit, ['payload' => $request->all(), 'response' => $responseData]);
                            ApiLoggerService::logEvent(
                                '/v1/payout-request',
                                $checkLimit,
                                'Payout Callback failed for merchant. URL: ' . ($merchant->callback_url ?? "N/A"),
                                $logPayload,
                                $response->body(),
                                'error'
                            );
                        } else {
                            Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->callback_url, $responseData);
                            ApiLoggerService::logEvent(
                                '/v1/payout-request',
                                'Callback attempted',
                                'Payout callback sent for merchant. URL: ' . ($merchant->callback_url ?? "N/A"),
                                $logPayload,
                                $responseData,
                                'info'
                            );
                        }
                    } else {
                        Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->callback_url, $responseData);
                        ApiLoggerService::logEvent(
                            '/v1/payout-request',
                            'Callback attempted',
                            'Payout callback sent for merchant. URL: ' . ($merchant->callback_url ?? "N/A"),
                            $logPayload,
                            $responseData,
                            'info'
                        );
                    }
                } else {
                    ApiLoggerService::logEvent(
                        '/v1/payout-request',
                        'Payout Callback Error',
                        'Invalid callback URL for merchant. URL: ' . ($merchant->callback_url ?? "N/A"),
                        $logPayload,
                        $response->body(),
                        'error'
                    );
                    Log::error("Invalid callback URL for merchant", ["url" => $merchant->callback_url]);
                }
            }
            return response()->json($responseData, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            Log::error('Paykuber payoutRequest exception: ' . $e);
            ApiLoggerService::logEvent(
                '/v1/payout-request',
                'Paykuber payoutRequest Exception',
                $e->getMessage(),
                null,
                null,
                'exception'
            );
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }

    public function utrUpdate(Request $request) // Tested & Ready
    {
        $rules = [
            'acc_id' => 'required|exists:merchant_infos,acc_id',
            'merchant_id' => 'required|string',
            'order_id' => 'required|string',
            'utr' => 'required|string',
        ];
        $messages = [
            'acc_id.required' => 'Account ID is required.',
            'acc_id.exists' => 'Invalid account id.',
            'merchant_id.required' => 'Merchant ID is required.',
            'order_id.required' => 'Order ID is required.',
            'utr.required' => 'UTR number is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }
        try {
            $apiToken = $request->attributes->get('api_token');
            $merchant = MerchantInfo::where('acc_id', $request->acc_id)->first();
            if (!$merchant || $merchant->merchant_status !== "Active") {
                return response()->json(['message' => 'Account is locked or customer does not exist! Please contact admin.'], 403);
            }
            $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->where('status', 'active')->first();
            if (!$merchantGateway) {
                return response()->json(['message' => 'You are not allowed to make requests.'], 403);
            }
            // Payload (callback data)
            $data = json_encode($request->all());
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($data, $secret);

            $logPayload = [
                'payload' => $request->all(),
                'signature' => $signature
            ];
            ApiLoggerService::logEvent(
                '/v1/utr-update',
                'Paykuber UTR update Request',
                'UTR update request generated for gateway Paykuber.',
                $logPayload,
            );
            $payload = [
                "order_id" => $request->order_id,
                "merchant_id" => $request->merchant_id,
                "utr" => $request->utr,
            ];
            $response = $this->makePaykuberRequest($payload, 'https://api1.paykuber.com/v2/utrUpdate', $apiToken);
            $responseData = $response->json();
            // Predefined messages
            $errorMessages = [
                'Please try again!(PT)',
                'Too many requests from this IP.(PT)',
                'Oops! Your request was not accepted, please try again.(PT)',
                'Unexpected response from payment gateway, please retry.(PT)',
                'Service is currently unavailable, please try again later.(PT)',
                'Something went wrong, please contact support.(PT)'
            ];
            // Ensure $responseData is not empty
            if (empty($responseData)) {
                Log::error("Empty response received from Paykuber", ["payload" => $payload, "response" => $response->body()]);
                return response()->json(['message' => $errorMessages[array_rand($errorMessages)]], 500);
            }
            ApiLoggerService::logEvent(
                '/v1/utr-update',
                'Paykuber UTR update Response',
                'UTR update response received from gateway Paykuber.',
                $logPayload,
                $response->body(),
                $response->successful() ? 'info' : 'error'
            );
            return response()->json($responseData, 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            Log::error('Paykuber utrUpdate exception: ' . $e);
            ApiLoggerService::logEvent(
                '/v1/utr-update',
                'Paykuber utrUpdate Exception',
                $e->getMessage(),
                null,
                null,
                'exception'
            );
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }

    public function checkBalance(Request $request) // Tested & Ready
    {
        ApiLoggerService::logEvent(
            '/v1/payout-request',
            'Balance Check Request',
            'Balance check request for gateway paykuber.',
            $request->all()
        );
        $rules = [
            'acc_id' => 'required|exists:merchant_infos,acc_id',
            'merchant_id' => 'required|string',
        ];
        $messages = [
            'acc_id.required' => 'Account ID is required.',
            'acc_id.exists' => 'Invalid account id.',
            'merchant_id.required' => 'Merchant ID is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }
        $merchant = MerchantInfo::where('acc_id', $request->acc_id)->where('merchant_status', 'Active')->first();
        if (!$merchant) {
            return response()->json(['message' => 'Your account is locked! Please contact admin.'], 402);
        }
        $wallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
        if (!$wallet) {
            $data = [
                'active_balance' => 0.00,
                'pending_balance' => 0.00,
                'roling_balance' => 0.00,
            ];
            return response()->json($data);
        } else {
            $data = [
                'active_balance' => $wallet->balance,
                'pending_balance' => $wallet->pending_balance,
                'roling_balance' => $wallet->roling_balance,
            ];
            return response()->json($data);
        }
    }

    public function payinTriggerCallback(Request $request)
    {
        return response()->json($request->all());
    }

    public function webHookHandler(Request $request) // New Logic Updated, Tested and Working as per 07-03-2025
    {
        Log::info('Paykuber Webhook Received:', $request->all());
        $webhookData = $request->input('data');
        // Validate required fields
        if (!isset($webhookData['order_id'], $webhookData['status'], $webhookData['amount'])) {
            Log::error("Invalid Webhook Data Received", ['data' => $webhookData]);
            return response()->json(['message' => 'Invalid Webhook Data'], 400);
        }
        $orderId = $webhookData['order_id'] ?? null;
        $tnxid = $webhookData['txn_id'] ?? null;
        $amount = (float) $webhookData['amount'];
        // Fetch Wallet and Transaction Details
        $walletTransaction = WalletTransaction::whereIn('status', ['pending', 'processing', 'initiated', 'queued'])
            ->where(function ($query) use ($orderId, $tnxid) {
                $query->where('transaction_id', $orderId);
                if ($tnxid) {
                    $query->orWhere('transaction_id', $tnxid);
                }
            })
            ->first();
        $transaction = Transaction::where('order_id', $orderId)->orWhere('order_id', $tnxid)->first();
        if (!$transaction) {
            Log::error("Transaction not found for order_id: {$orderId} transaction_id: {$tnxid}");
            return response()->json(['message' => 'Transaction not found.'], 404);
        }
        $incomingStatus = strtolower($webhookData['status']);
        $mappedStatus = match ($incomingStatus) {
            'completed' => 'successful',
            'processing' => 'processing',
            'initiated' => 'initiated',
            'pending' => 'pending',
            'failed' => 'failed',
            'expired' => 'expired',
            'queued' => 'queued',
            default => 'pending',
        };
        // Always update transaction status
        $transaction->update([
            'status' => $mappedStatus,
            'response_data' => json_encode($webhookData),
        ]);
        if ($walletTransaction) {
            // Always update wallet transaction status
            if ($walletTransaction->status != $mappedStatus) {
                $walletTransaction->update([
                    'status' => $mappedStatus
                ]);
            }
            $merchant = MerchantInfo::where('merchant_id', $walletTransaction->merchant_id)->first();
            if (!$merchant) {
                return response()->json(['message', 'Unauthorized access! Request blocked.'], 403);
            }
            $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->first();
            $merchantWallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
            $data = json_encode($transaction->request_data);
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($data, $secret);
            if ($merchantWallet && $walletTransaction->type === 'debit' && ($mappedStatus === 'failed' || $mappedStatus === 'expired')) {
                $oldRolingAmt = $merchantWallet->roling_balance;
                $oldBalance = $merchantWallet->balance;
                $merchantWallet->update([
                    'balance' => (float)$oldBalance + ((float)$walletTransaction->amount + (float)$walletTransaction->charge),
                    'roling_balance' => (float)$oldRolingAmt - ((float)$walletTransaction->amount + (float)$walletTransaction->charge)
                ]);
                $walletTransaction->update([
                    'current_balance' => $merchantWallet->balance,
                    'current_pending_balance' => $merchantWallet->pending_balance,
                    'current_hold_balance' => $merchantWallet->roling_balance
                ]);
                Log::info("Merchant wallet updated for transaction id {$request->order_id}", [
                    'wallet_transaction_charge' => $walletTransaction->charge,
                    'wallet_transaction_amount' => $walletTransaction->amount,
                    'old_balance' => $oldBalance,
                    'new_balance' => $merchantWallet->balance,
                    'old_roling_balance' => $oldRolingAmt,
                    'new_roling_balance' => $merchantWallet->roling_balance
                ]);
                return response()->json(['message' => 'Merchant wallet updated successfully.'], 200);
            }
            if ($merchantWallet && $mappedStatus === 'successful') {
                // Fetch Merchant Gateway
                $merchantGateway = MerchantGateway::where('mid', $walletTransaction->merchant_id)
                    ->where('status', 'active')
                    ->first();
                if (!$merchant || !$merchantGateway) {
                    Log::error("Merchant or Merchant Gateway not found for order_id: {$orderId} transaction_id: {$tnxid}");
                    return response()->json(['message' => 'Merchant or Gateway not found.'], 400);
                }
                // Code added on 20-03-2025 by Ketan Start
                $ptGateway = PaymentGateway::find($merchantGateway->payout_gateway_id);
                if (!$ptGateway) {
                    Log::error("Payment gateway configuration error for order_id: {$orderId} transaction_id: {$tnxid}");
                    return response()->json(['message' => 'Payment gateway configuration error! Please contact support.'], 403);
                }
                if ($walletTransaction->type == 'credit') {
                    switch ($ptGateway->gateway_type) {
                        case 'payin':
                        case 'both':
                            break;
                        default:
                            Log::error("Payment gateway type error for order_id: {$orderId} transaction_id: {$tnxid}");
                            return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
                    }
                } elseif ($walletTransaction->type == 'debit') {
                    switch ($ptGateway->gateway_type) {
                        case 'payout':
                        case 'both':
                            break;
                        default:
                            Log::error("Payment gateway type error for order_id: {$orderId} transaction_id: {$tnxid}");
                            return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
                    }
                } else {
                    return response()->json(['message' => 'Transaction type error! Please contact support.'], 403);
                }
                // Calculate gateway charge
                $charges = 0;
                $gatewayCharge = 0;
                $gatewayChargeType = null;
                if ($walletTransaction->type == 'credit') {
                    if ($amount >= 0 && $amount < $merchantGateway->payin_switch_amount) {
                        $gatewayCharge = $merchantGateway->payin_charge ?? 0;
                        $gatewayChargeType = $merchantGateway->payin_charge_type ?? "flat";
                        if ($gatewayChargeType === "flat") {
                            $charges = $gatewayCharge;
                        } elseif ($gatewayChargeType === "percent") {
                            $charges = ($amount * $gatewayCharge) / 100.00;
                        } else {
                            $charges = 0.0;
                        }
                    } elseif ($amount >= $merchantGateway->payin_switch_amount) {
                        $gatewayCharge = $merchantGateway->payin_charge2 ?? 0;
                        $gatewayChargeType = $merchantGateway->payin_charge2_type ?? "flat";
                        if ($gatewayChargeType === "flat") {
                            $charges = $gatewayCharge;
                        } elseif ($gatewayChargeType === "percent") {
                            $charges = ($amount * $gatewayCharge) / 100.00;
                        } else {
                            $charges = 0.0;
                        }
                    } else {
                        $charges = 0.0;
                    }
                } elseif ($walletTransaction->type == 'debit') {
                    if ($amount >= 0 && $amount < $merchantGateway->payout_switch_amount) {
                        $gatewayCharge = $merchantGateway->payout_charge ?? 0;
                        $gatewayChargeType = $merchantGateway->payout_charge_type ?? "flat";
                        if ($gatewayChargeType === "flat") {
                            $charges = $gatewayCharge;
                        } elseif ($gatewayChargeType === "percent") {
                            $charges = ($amount * $gatewayCharge) / 100.00;
                        } else {
                            $charges = 0.0;
                        }
                    } elseif ($amount >= $merchantGateway->payout_switch_amount) {
                        $gatewayCharge = $merchantGateway->payout_charge2 ?? 0;
                        $gatewayChargeType = $merchantGateway->payout_charge2_type ?? "flat";
                        if ($gatewayChargeType === "flat") {
                            $charges = $gatewayCharge;
                        } elseif ($gatewayChargeType === "percent") {
                            $charges = ($amount * $gatewayCharge) / 100.00;
                        } else {
                            $charges = 0.0;
                        }
                    } else {
                        $charges = 0.0;
                    }
                } else {
                    // Send Callback Notification if URL exists
                    $url = $walletTransaction->type === "debit" ? ($merchant->callback_url ?? null) : ($merchant->webhook_url ?? null);
                    if ($url) {
                        try {
                            Log::info("Sending X-Signature: " . $signature . " on URL: " . $url);
                            $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $request->all());
                            if (!$response->successful()) {
                                Log::error("Merchant Callback Failed", ['response' => $response->body()]);
                            }
                        } catch (Exception $e) {
                            Log::error("Merchant Callback Failed HTTP Request Exception: " . $e->getMessage());
                        }
                    }
                    Log::error("Wallet transaction type is not correct!");
                    response()->json(['message' => 'Wallet transaction type is not correct!'], 400);
                }
                $agentCommission = $this->getAgentCommission($merchant->agent_id ?? 0, $amount, $walletTransaction->type);
                $paykuberCommission = $this->getPaykuberCommission($ptGateway, $amount, $walletTransaction->type);
                // Update Wallet Transaction with Charges
                $walletTransaction->update([
                    'utr' => $webhookData['utr'] ?? null,
                    'charge' => $charges,
                    'amount' => $amount,
                    'status' => $mappedStatus,
                    'pt_agent_commission' => $agentCommission,
                    'pt_gateway_charges' => $paykuberCommission
                ]);
                // Code added on 20-03-2025 by Ketan End
                if ($walletTransaction->type === 'credit') {
                    $newBalance = $amount - $charges;
                    // Rolling Reserve Calculation
                    $splittedAmount = $this->rollingReserve(
                        $walletTransaction->merchant_id,
                        $walletTransaction->transaction_id,
                        $amount,
                        $newBalance,
                        $merchant->rolling_charge
                    );
                    $oldPendingAmt = (float)$merchantWallet->pending_balance;
                    $oldRollingAmt = (float)$merchantWallet->roling_balance;
                    $merchantWallet->update([
                        'pending_balance' => $oldPendingAmt + (float)$splittedAmount['pending_amount'],
                        'roling_balance' => $oldRollingAmt + (float)$splittedAmount['rolling_amount'],
                    ]);
                    // Code added on 20-03-2025 by Ketan Start
                    $walletTransaction->update([
                        'current_balance' => $merchantWallet->balance,
                        'current_pending_balance' => $merchantWallet->pending_balance,
                        'current_hold_balance' => $merchantWallet->roling_balance
                    ]);
                    // Code added on 20-03-2025 by Ketan End
                    Log::info("Merchant wallet updated for transaction id {$request->order_id}", [
                        'old_pending_amount' => $oldPendingAmt,
                        'old_roling_amount' => $oldRollingAmt,
                        'new_pending_amount' => $oldPendingAmt + (float)$splittedAmount['pending_amount'],
                        'new_roling_amount' => $oldRollingAmt + (float)$splittedAmount['rolling_amount'],
                    ]);
                } elseif ($walletTransaction->type === 'debit') { // Handle Payouts
                    $newBalance = $walletTransaction->amount + $walletTransaction->charge;
                    $oldRolingAmt = (float)$merchantWallet->roling_balance;
                    $merchantWallet->update([
                        'roling_balance' => $oldRolingAmt - $newBalance,
                    ]);
                    // Code added on 20-03-2025 by Ketan Start
                    $walletTransaction->update([
                        'current_balance' => $merchantWallet->balance,
                        'current_pending_balance' => $merchantWallet->pending_balance,
                        'current_hold_balance' => $merchantWallet->roling_balance
                    ]);
                    // Code added on 20-03-2025 by Ketan End
                    Log::info("Merchant wallet updated for transaction id {$request->order_id}", [
                        'old_roling_balance' => $oldRolingAmt,
                        'new_roling_balance' => $oldRolingAmt - $newBalance
                    ]);
                }
            }
            // Send Callback Notification if URL exists
            $url = $walletTransaction->type === "debit" ? ($merchant->callback_url ?? null) : ($merchant->webhook_url ?? null);
            if ($url) {
                try {
                    Log::info("Sending X-Signature: " . $signature . " on URL: " . $url);
                    $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $request->all());
                    if (!$response->successful()) {
                        Log::error("Merchant Callback Failed", ['response' => $response->body()]);
                    }
                } catch (Exception $e) {
                    Log::error("Merchant Callback Failed HTTP Request Exception: " . $e->getMessage());
                }
            }
        }
        Log::info("Transaction {$tnxid} updated successfully.", ['status' => $mappedStatus]);
        return response()->json(['message' => 'Transaction updated successfully.'], 200);
    }

    public function paymentRedirecter($id)
    {
        // Fetch transaction containing the ID in response_data
        $transaction = ApiLog::where('response', 'LIKE', "%$id%")->first();

        // Check if transaction exists and response_data is valid JSON
        if (!$transaction || !$transaction->response) {
            return abort(404, 'URL does not exist!');
        }

        $responseData = json_decode($transaction->response);

        // Ensure data and paymentLink exist
        if (!isset($responseData->data->paymentLink)) {
            return abort(404, 'URL does not exist!');
        }

        $url = $responseData->data->paymentLink;

        // Redirect to the payment URL
        return redirect()->away($url);
    }
}
