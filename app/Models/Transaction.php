<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $fillable = ['order_id', 'gateway', 'api_token', 'request_data', 'response_data', 'trx_type', 'status'];
    protected $casts = [
        'request_data' => 'json',
        'response_data' => 'json',
    ];
    protected $hidden = ['api_token'];
}
