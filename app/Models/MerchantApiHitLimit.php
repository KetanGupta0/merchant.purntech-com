<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantApiHitLimit extends Model
{
    protected $table = "merchant_api_hit_limits";
    protected $primaryKey = "id";
    protected $fillable = [
        'merchant_id',
        'payin_hits',
        'payin_hit_time',
        'payin_hit_limit',
        'payout_hits',
        'payout_hit_time',
        'payout_hit_limit',
        'balance_check_hits',
        'balance_check_hits_time',
        'balance_check_hits_limit',
        'transaction_check_hits',
        'transaction_check_hits_time',
        'transaction_check_hits_limit',
        'webhook_hits',
        'webhook_hits_time',
        'webhook_hits_limit',
        'callback_hits',
        'callback_hits_time',
        'callback_hits_limit',
        'overall_hit_limit',
        'status'
    ];
}
