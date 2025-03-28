<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $table = 'beneficiaries';
    protected $fillable = [
        'merchant_id',
        'name',
        'email',
        'mobile',
        'account_no',
        'ifsc',
        'bank_name',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'status'
    ];
}
