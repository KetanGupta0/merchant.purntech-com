<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $table = 'payment_gateways';
    protected $fillable = [
        'gateway_name',
        'config',
        'parameters',
        'gateway_type',
        'payin_switch_amount',
        'payin_charges_below',
        'payin_charges_above',
        'payin_charges_type_below',
        'payin_charges_type_above',
        'payout_switch_amount',
        'payout_charges_below',
        'payout_charges_above',
        'payout_charges_type_below',
        'payout_charges_type_above',
        'status'
    ];
    protected $casts = [
        'config' => 'json',
        'parameters' => 'json',
    ];
}
