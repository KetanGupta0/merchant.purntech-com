<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = "agents";
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'agent_profile',
        'plain_password',
        'payin_switch_amount',
        'payin_commission_below',
        'payin_commission_type_below',
        'payin_commission_above',
        'payin_commission_type_above',
        'payout_switch_amount',
        'payout_commission_below',
        'payout_commission_type_below',
        'payout_commission_above',
        'payout_commission_type_above',
        'status'
    ];
}
