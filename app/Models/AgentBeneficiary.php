<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentBeneficiary extends Model
{
    protected $table = 'agent_beneficiaries';
    protected $fillable = [
        'agent_id',
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
