<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $table = "settlements";
    protected $fillable = [
        'merchant_id',
        'order_id',
        'transaction_id',
        'settlement_amount',
        'merchant_fee',
        'tax_amount',
        'tax_amount_type',
        'reserved_amount',
        'bank_fee',
        'bank_fee_type',
        'net_amount',
        'settlement_status',
        'failure_reason',
        'upi_id',
        'utr_number',
        'bank_name',
        'bank_account',
        'bank_ifsc',
        'reference_number',
        'settlement_mode',
        'settlement_method',
        'settlement_type',
        'initiated_by',
        'remarks',
        'payment_gateway',
        'settlement_batch_id',
        'currency',
        'wallet_balance_before',
        'wallet_balance_final'
    ];
    protected $casts = [
        'initiated_by' => 'json'
    ];
}
