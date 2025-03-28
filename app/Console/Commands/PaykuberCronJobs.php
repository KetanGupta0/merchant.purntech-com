<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\MerchantGateway;
use App\Models\MerchantInfo;
use App\Models\MerchantRollingReserve;
use App\Models\MerchantWallet;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaykuberCronJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:paykuber-cron-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->transactionsUpdate();
    }

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

    public function transactionsUpdate()  // New Logic Updated, Tested and Working as per 07-03-2025
    {
        $txns = Transaction::whereNotIn('status', ['successful', 'completed','expired','failed'])->get();
        if ($txns->isEmpty()) {
            return;
        }
        foreach ($txns as $transaction) {
            // Check if transaction is older than 48 hours
            if($transaction->created_at < Carbon::now()->subHours(48)){
                $transaction->status = 'expired';
                $transaction->save();
                $walletTransaction = WalletTransaction::where('transaction_id', $transaction->order_id)
                ->where(function ($query) {
                    $query->where("status", "!=", "completed")
                        ->where("status", "!=", "successful");
                })->first();
                if($walletTransaction){
                    $walletTransaction->status = 'expired';
                    $walletTransaction->save();
                }
                Log::info('Transaction expired: ' . $transaction->order_id);
                continue;
            }
            $isCompletedOrFailed = false;
            $walletTransaction = WalletTransaction::where('transaction_id', $transaction->order_id)
                ->where(function ($query) {
                    $query->where("status", "!=", "completed")
                        ->where("status", "!=", "successful");
                })->first();
            if (!$walletTransaction) {
                // New Code on 18-03-2025
                $walletTransaction = WalletTransaction::where('transaction_id', $transaction->order_id)->first();
                if($walletTransaction){
                    $isCompletedOrFailed = true;
                }else{
                    Log::error("Wallet transaction not found for transaction_id: " . $transaction->order_id);
                    continue;
                }
            }
            $merchant = MerchantInfo::where('merchant_id', $walletTransaction->merchant_id)->first();
            if (!$merchant) {
                Log::error("Merchant not found for transaction_id: " . $transaction->order_id);
                continue;
            }
            $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->first();
            if (!$merchantGateway) {
                Log::error("Merchant gateway not found for transaction_id: " . $transaction->order_id);
                continue;
            }
            // Code added on 20-03-2025 by Ketan Start
            $ptGatewayId = $walletTransaction->type == 'credit' ? $merchantGateway->payin_gateway_id : $merchantGateway->payout_gateway_id;
            $ptGateway = PaymentGateway::find($ptGatewayId);
            if (!$ptGateway) {
                Log::error("Gateway configuration error for transaction_id: " . $walletTransaction->transaction_id);
                continue;
            }
            if($walletTransaction->type == 'credit'){
                switch($ptGateway->gateway_type){
                    case 'payin':
                    case 'both':
                        break;
                    default:
                        Log::error("Gateway type error for transaction_id: " . $walletTransaction->transaction_id);
                        continue 2;
                }
            }elseif($walletTransaction->type == 'debit'){
                switch($ptGateway->gateway_type){
                    case 'payout':
                    case 'both':
                        break;
                    default:
                        Log::error("Gateway type error for transaction_id: " . $walletTransaction->transaction_id);
                        continue 2;
                }
            }else{
                Log::error("Transaction type error for transaction_id: " . $walletTransaction->transaction_id);
                continue;
            }
            // Code added on 20-03-2025 by Ketan End
            $apiToken = $merchantGateway->api_key ?? null;
            $payload = [
                'type' => $walletTransaction->type === 'credit' ? 'payin' : 'payout',
                'merchant_id' => $merchantGateway->merchant_id ?? null,
                'order_id' => $transaction->order_id
            ];
            $response = $this->makePaykuberRequest($payload, 'https://api1.paykuber.com/api/seamless/txnStatus', $apiToken);
            $responseData = $response->json();
            if (isset($responseData['error']) || !isset($responseData['data']['status'])) {
                Log::error("Transaction not found for transaction_id: " . $transaction->order_id, ['response' => $responseData]);
                continue;
            }
            $incomingStatus = strtolower($responseData['data']['status']);
            $mappedStatus = match ($incomingStatus) {
                'completed' => 'successful',
                'processing' => 'processing',
                'initiated' => 'initiated',
                'failed' => 'failed',
                'expired' => 'expired',
                'queued' => 'queued',
                default => 'pending',
            };
            // Update status if any status change found
            if ($transaction) {
                if ($transaction->status != $mappedStatus) {
                    $oldTrnxStatus = $transaction->status;
                    $transaction->update([
                        'status' => $mappedStatus,
                        'response_data' => $responseData,
                    ]);
                    Log::info('Transaction Status Updated',[
                        'order_id' => $transaction->order_id,
                        'old_status' => $oldTrnxStatus,
                        'new_status' => $mappedStatus
                    ]);
                }
            }

            if($isCompletedOrFailed){
                continue;
            }

            $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->first();
            $merchantWallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
            $amount = (float)$responseData['data']['amount'];
            $data = json_encode($merchantGateway->request_data);
            $secret = $merchantGateway->salt_key; // Salt Key from database
            $signature = $this->encryptData($data, $secret);
            if ($walletTransaction && $merchantGateway && $merchantWallet) {
                // **Only update balance when status is 'successful'**
                if ($mappedStatus !== 'successful') {
                    if($walletTransaction->type === 'debit' && ($mappedStatus === 'failed' || $mappedStatus === 'expired')){
                        $oldRolingAmt = $merchantWallet->roling_balance;
                        $oldBalance = $merchantWallet->balance;
                        $newBalance = $walletTransaction->amount + $walletTransaction->charge;
                        $merchantWallet->update([
                            'balance' => $oldBalance + $newBalance,
                            'roling_balance' => $oldRolingAmt - $newBalance
                        ]);
                        Log::info("Merchant wallet updated for transaction id {$transaction->order_id}", [
                            'wallet_transaction_charge' => $walletTransaction->charge,
                            'wallet_transaction_amount' => $walletTransaction->amount,
                            'old_balance' => $oldBalance,
                            'new_balance' => $merchantWallet->balance,
                            'old_roling_balance' => $oldRolingAmt,
                            'new_roling_balance' => $merchantWallet->roling_balance
                        ]);
                    }
                    if ($walletTransaction->status != $mappedStatus) {
                        $walletTransaction->update([
                            'status' => $mappedStatus
                        ]);
                        Log::info("Wallet transaction updated for transaction id: {$transaction->order_id}");
                    }
                    continue;
                }
                // Code added on 20-03-2025 by Ketan Start
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
                    Log::error("Invalid wallet transaction type found for transaction id: {$walletTransaction->transaction_id}");
                    continue;
                }
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
                        Log::info("Merchant wallet updated for transaction id {$transaction->order_id}", [
                            'old_pending_amount' => $oldPendingAmt,
                            'old_roling_amount' => $oldRollingAmt,
                            'new_pending_amount' => $oldPendingAmt + (float)$splittedAmount['pending_amount'],
                            'new_roling_amount' => $oldRollingAmt + (float)$splittedAmount['rolling_amount'],
                        ]);
                    }
                    if ($walletTransaction->type == 'debit') {
                        $newBalance = $amount + $charges;
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
                        Log::info("Merchant wallet updated for transaction id {$transaction->order_id}", [
                            'old_roling_balance' => $oldRolingAmt,
                            'new_roling_balance' => $oldRolingAmt - $newBalance
                        ]);
                    }
                    Log::info("Wallet transaction updated for transaction id: {$transaction->order_id}");
                    // Send Callback Notification if URL exists
                    $url = $walletTransaction->type === "debit" ? ($merchant->callback_url ?? null) : ($merchant->webhook_url ?? null);
                    if ($url) {
                        try {
                            if($walletTransaction->type === "debit" && strtolower($responseData['data']['status']) === "failed"){
                                $newpayload = ["message" => "Payout failed!"];
                                $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $newpayload);
                                if (!$response->successful()) {
                                    Log::error("Merchant Callback Failed", ['response' => $response->body()]);
                                }else{
                                    Log::info("Merchant Callback Successful", ['response' => $response->body()]);
                                }
                            }else{
                                $response = Http::withHeaders(['Content-Type' => 'application/json', 'X-Signature' => $signature])->post($url, $responseData);
                                if (!$response->successful()) {
                                    Log::error("Merchant Webhook Failed", ['response' => $response->body()]);
                                }else{
                                    Log::info("Merchant Webhook Successful", ['response' => $response->body()]);
                                }
                            }
                        } catch (Exception $e) {
                            Log::error("HTTP Request Exception: " . $e->getMessage());
                        }
                    }
                }
            }
        }
    }
}