<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\WalletTransaction;
use Exception;

class ProcessPayRequestPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $merchant;
    protected $wallet;
    protected $transaction;
    protected $payload;
    protected $responseData;
    protected $charges;
    protected $logPayload;

    /**
     * Create a new job instance.
     */
    public function __construct($merchant, $wallet, $transaction, $payload, $responseData, $charges, $logPayload)
    {
        $this->merchant     = $merchant;
        $this->wallet       = $wallet;
        $this->transaction  = $transaction;
        $this->payload      = $payload;
        $this->responseData = $responseData;
        $this->charges      = $charges;
        $this->logPayload   = $logPayload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Create or update a wallet transaction record based on the API response
            WalletTransaction::create([
                'merchant_id'              => $this->merchant->merchant_id,
                'amount'                   => $this->payload['amount'],
                'charge'                   => $this->charges,
                'type'                     => 'credit',
                'transaction_id'           => $this->payload['order_id'],
                'remarks'                  => 'Payin request initiated',
                'utr'                      => null,
                'acc_no'                   => $this->payload['account_number'] ?? null,
                'ifsc'                     => $this->payload['bank_ifsc'] ?? null,
                'beneficiary_name'         => $this->payload['bene_name'] ?? null,
                'current_balance'          => $this->wallet->balance,
                'current_pending_balance'  => $this->wallet->pending_balance,
                'current_hold_balance'     => $this->wallet->roling_balance,
                'settlement_status'        => 'not settled',
                'pt_gateway_charges'       => 0,
                'pt_agent_commission'      => 0,
                'status'                   => 'pending',
                'visibility'               => 'visible'
            ]);

            // You can add additional logging or processing here if needed

        } catch (Exception $e) {
            Log::error('Exception in ProcessPayRequestPostJob: ' . $e->getMessage());
        }
    }
}
