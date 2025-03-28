<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'admin_id';
    public $fillable = [
        'admin_name',
        'admin_phone',
        'admin_phone2',
        'admin_email',
        'admin_business_id',
        'admin_profile_pic',
        'admin_city',
        'admin_state',
        'admin_country',
        'admin_zip_code',
        'admin_landmark',
        'payin_min_amt',
        'payin_max_amt',
        'payout_min_amt',
        'payout_max_amt',
        'admin_password',
        'admin_plain_password',
        'admin_type',
        'admin_status',
    ];
}
