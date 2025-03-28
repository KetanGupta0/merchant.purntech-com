<?php

namespace App\Console\Commands;

use App\Models\MerchantApiHitLimit;
use Illuminate\Console\Command;

class ApiHitsReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:api-hits-reset';

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
        $this->apiHitReset();
    }

    public function apiHitReset(){
        $apiHitLimits = MerchantApiHitLimit::where('status','active')->get();
        foreach($apiHitLimits as $limit){
            $limit->update([
                'payin_hits' => 0,
                'payout_hits' => 0,
                'balance_check_hits' => 0,
                'transaction_check_hits' => 0,
                'webhook_hits' => 0,
                'callback_hits' => 0
            ]);
        }
    }
}
