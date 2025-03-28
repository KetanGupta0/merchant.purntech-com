<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantInfo extends Model
{
    protected $table = 'merchant_infos';
    protected $primaryKey = 'merchant_id';

    public $fillable = [
        'acc_id',
        'merchant_name',
        'merchant_phone',
        'merchant_phone2',
        'merchant_email',
        'merchant_aadhar_no',
        'merchant_pan_no',
        'merchant_profile',
        'merchant_city',
        'merchant_state',
        'merchant_country',
        'merchant_zip',
        'merchant_landmark',
        'merchant_password',
        'merchant_plain_password',
        'rolling_charge',
        'payout_v_charge',
        'payout_failed_hits',
        'payin_hit_charge',
        'payin_failed_hits',
        'payout_mode',
        'settlement_type',
        'callback_url',
        'webhook_url',
        'merchant_is_onboarded',
        'merchant_is_verified',
        'merchant_status',
        'ip_protection',
        'agent_id'
    ];
}
