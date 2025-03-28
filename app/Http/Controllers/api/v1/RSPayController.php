<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ApiLog;
use App\Models\MerchantApiHitLimit;
use App\Models\MerchantGateway;
use App\Models\MerchantInfo;
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

class RSPayController extends Controller
{
    private function encryptData($data, $secretKey) // Code added on 14-03-2025 by ketan
    {
        $iv = random_bytes(16); // Generate a 16-byte IV
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $secretKey, 0, $iv);
        return base64_encode($iv . $encrypted); // Combine IV + encrypted data
    }
    private function generateSHA256Signature(array $params, string $key): string
    {
        $filteredParams = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });
        ksort($filteredParams);
        $pairs = [];
        foreach ($filteredParams as $k => $v) {
            $pairs[] = "{$k}={$v}";
        }
        $joinedParams = implode('&', $pairs);
        $stringToSign = $joinedParams . "&key=" . $key;
        // Log::info("String to Sign: " . $stringToSign);
        return hash('sha256', $stringToSign);
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
        $previousHits = ApiLog::where('event', 'Callback attempted')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Callback hit limit reached'], 429);
        }
        $apiHits->increment('callback_hits');
        return null;
    }
    private function countWebhookkHits(MerchantApiHitLimit $apiHits, $accid)
    {
        $limit = $apiHits->webhook_hits_limit;
        $time = $apiHits->webhook_hits_time;
        $previousHits = ApiLog::where('event', 'Webhook attempted')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Webhook hit limit reached'], 429);
        }
        $apiHits->increment('webhook_hits');
        return null;
    }

    public function payRequest(Request $request)
    {
        ApiLoggerService::logEvent(
            '/pay/v1/pay-request',
            'Payin Hit',
            'User is initiating a payin request.',
            $request->all(),
            null,
            'info'
        );
        $admin = Admin::find(1);
        if (!$admin) {
            return response()->json(['message' => 'Something went wrong! Please contact admin.'], 500);
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
            'amount.min' => 'Minimum Amount must be ' . $admin->payin_min_amt . '.',
            'amount.max' => 'Maximum Amount must be ' . $admin->payin_max_amt . '.',
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

        $key = env('PAYMENT_SECRET_KEY', 'rspay_token_1742628506872');

        $sign = $this->generateSHA256Signature([
            'merchantId'        => $request->merchant_id,
            'merchantOrderId'   => $request->order_id,
            'amount'            => $request->amount,
            'paymentCurrency'   => 'INR',
            'notifyUrl'         => $request->callback_url,
            'redirectUrl'       => $request->redirect_url,
            'userName'          => 'INR222518',
            'type'              => 2,
            'ext'               => 'test'
        ], $key);

        $payload = [
            'merchantId'        => $request->merchant_id,
            'merchantOrderId'   => $request->order_id,
            'amount'            => $request->amount,
            'paymentCurrency'   => 'INR',
            'notifyUrl'         => $request->callback_url,
            'redirectUrl'       => $request->redirect_url,
            'userName'          => 'INR222518',
            'ext'               => 'test',
            'type'              => 2,
            'sign'              => $sign
        ];
        // return response()->json([$payload], 200);
        // Send the POST request to the external API
        $response = Http::post('https://test.cp.purntech.com/api/pay', $payload);

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
            $reqData = json_encode($request->all());
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($reqData, $secret);
            $logPayload = [
                'payload' => $request->all(),
                'signature' => $signature
            ];
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
            if ($merchantGateway->gst_enabled === "yes") {
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
            $transaction = Transaction::create([
                'order_id' => $request->order_id,
                'gateway' => 'RSPay',
                'api_token' => is_object($apiToken) ? $apiToken->token : $apiToken,
                'request_data' => $logPayload
            ]);
            $walletTxn = WalletTransaction::create([
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
            if (($merchant->webhook_url != null && $merchant->webhook_url != '') && filter_var($merchant->webhook_url, FILTER_VALIDATE_URL)) {
                $merchantApiHitLimit = MerchantApiHitLimit::where('merchant_id', $merchant->merchant_id)->where('status', 'active')->first();
                if ($merchantApiHitLimit) {
                    $checkLimit = $this->countWebhookkHits($merchantApiHitLimit, $request->acc_id);
                    if ($checkLimit) {
                        Log::error($checkLimit, ['payload' => $request->all(), 'response' => $response->json()]);
                        ApiLoggerService::logEvent(
                            '/pay/v1/pay-request',
                            $checkLimit,
                            'Payin Webhook failed for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                            $logPayload,
                            $response->body(),
                            'error'
                        );
                    } else {
                        Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->webhook_url, $response->json());
                        ApiLoggerService::logEvent(
                            '/pay/v1/pay-request',
                            'Webhook attempted',
                            'Payin Webhook sent for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                            $logPayload,
                            $response->json(),
                            'info'
                        );
                    }
                } else {
                    Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($merchant->webhook_url, $response->json());
                    ApiLoggerService::logEvent(
                        '/pay/v1/pay-request',
                        'Webhook attempted',
                        'Payin Webhook sent for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                        $logPayload,
                        $response->json(),
                        'info'
                    );
                }
            } else {
                Log::error("Invalid webhook URL for merchant", ["url" => $merchant->webhook_url]);
                ApiLoggerService::logEvent(
                    '/pay/v1/pay-request',
                    'Payin Webhook Error',
                    'Invalid webhook URL for merchant. URL: ' . ($merchant->webhook_url ?? "N/A"),
                    $logPayload,
                    $response->json(),
                    'error'
                );
            }
            if ($response->successful()) {
                $data = $response->json();
                ApiLoggerService::logEvent(
                    '/pay/v1/pay-request',
                    'RSPay Payin Response',
                    'Received response from the RSPay gateway.',
                    $logPayload,
                    $data,
                    'info'
                );
                $transaction->update([
                    'response_data' => $data
                ]);
                // Check for a valid payment URL in the API response
                if (isset($data['status']) && $data['status'] === 200 && isset($data['data']['payUrl'])) {
                    $paymentUrl = $data["data"]["payUrl"];
                    parse_str(parse_url($paymentUrl, PHP_URL_QUERY), $queryParams);
                    $uriId = $queryParams['rptNo'] ?? null;
                    $temperedResponse = [
                        "status" => $data["status"],
                        "message" => $data["message"],
                        "data" => [
                            "ext" => $data["data"]["ext"] ?? null,
                            "merchantId" => $data["data"]["merchantId"] ?? null,
                            "merchantOrderId" => $data["data"]["merchantOrderId"] ?? null,
                            "orderId" => $data["data"]["orderId"] ?? null,
                            "payUrl" => $uriId ? url('/pay2-' . $uriId) : null,
                            "amount" => $data["data"]["amount"] ?? null,
                            "paymentCurrency" => $data["data"]["paymentCurrency"] ?? null
                        ]
                    ];
                    return response()->json([$temperedResponse], 200)->header('X-Signature', $signature)->header('Content-Type', 'application/json');
                } else {
                    // Handle case when payment URL is not returned or API error occurred
                    $walletTxn->update([
                        'status' => 'expired'
                    ]);
                    $transaction->update([
                        'status' => 'expired'
                    ]);
                    return response()->json([$data], 400);
                }
            } else {
                // Log the error or inspect $response->body() for details
                $walletTxn->update([
                    'status' => 'expired'
                ]);
                $transaction->update([
                    'status' => 'expired'
                ]);
                return response()->json([$response->json()], 400);
            }
        } catch (Exception $e) {
            ApiLoggerService::logEvent(
                '/pay/v1/pay-request',
                'RSPay Payin Exception',
                $e->getMessage(),
                $logPayload,
                $response->json(),
                'error'
            );
            Log::error("RSPay Payin Exception: " . $e->getMessage());
        }
    }

    public function seamlessTxnStatus(Request $request){
        return response()->json($request->all());
    }

    public function payoutRequest(Request $request){
        return response()->json($request->all());
    }

    public function utrUpdate(Request $request){
        return response()->json($request->all());
    }

    public function checkBalance(Request $request){
        return response()->json($request->all());
    }

    public function payinTriggerCallback(Request $request){
        return response()->json($request->all());
    }

    public function paymentRedirecter($id)
    {
        $transaction = ApiLog::where('response', 'LIKE', "%$id%")->first();
        if (!$transaction || !$transaction->response) {
            return abort(404, 'URL does not exist!');
        }
        $responseData = json_decode(json_encode($transaction->response));
        if (!isset($responseData->data->payUrl)) {
            return abort(404, 'URL does not exist!');
        }
        $url = $responseData->data->payUrl;
        return redirect()->away($url);
    }
}
