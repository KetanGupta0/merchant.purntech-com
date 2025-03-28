<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantRollingReserve extends Model
{
    protected $table = 'merchant_rolling_reserves';
    protected $fillable = [
        'transaction_id',
        'merchant_id',
        'transaction_amount',
        'reserve_amount',
        'held_date',
        'release_date',
        'status'
    ];
}
