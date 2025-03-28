<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    protected $table = "account_details";
    protected $primaryKey = "acc_id";
    public $fillable = [
        'acc_merchant_id',
        'acc_business_id',
        'acc_account_number',
        'acc_bank_name',
        'acc_branch_name',
        'acc_ifsc_code',
        'acc_micr_code',
        'acc_swift_code',
        'acc_account_type',
        'acc_status',
        'network_type',
        'wallet_address',
    ];
}
