<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantWallet extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id', 'balance', 'pending_balance', 'roling_balance'];

    public function merchant()
    {
        return $this->belongsTo(MerchantInfo::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
