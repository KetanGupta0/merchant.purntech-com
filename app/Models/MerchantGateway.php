<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantGateway extends Model
{
    protected $table = 'merchant_gateways';
    protected $fillable = [
        'mid',
        'payin_gateway_id',
        'payout_gateway_id',
        'api_key',
        'merchant_id',
        'salt_key',
        'payin_switch_amount',
        'payin_charge',
        'payin_charge_type',
        'payin_charge2',
        'payin_charge2_type',
        'payout_switch_amount',
        'payout_charge',
        'payout_charge_type',
        'payout_charge2',
        'payout_charge2_type',
        'tax_switch_amount',
        'tax',
        'tax_type',
        'tax2',
        'tax2_type',
        'bank_fee_switch_amount',
        'bank_fee',
        'bank_fee_type',
        'bank_fee2',
        'bank_fee2_type',
        'settlement_time',
        'gst_enabled',
        'gst_percentage',
        'status'
    ];
    protected $casts = [
        'settlement_time' => 'json'
    ];
}