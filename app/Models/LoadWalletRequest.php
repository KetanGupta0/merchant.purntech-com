<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadWalletRequest extends Model
{
    protected $table = 'load_wallet_requests';
    protected $fillable = [
        'merchant_id',
        'transaction_id',
        'wallet_address',
        'wallet_qr',
        'send_amount',
        'get_amount',
        'tax',
        'other_charges',
        'conversion_rate',
        'send_currency',
        'get_currency',
        'status'
    ];
}
