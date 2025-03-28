<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';
    protected $fillable = [
        'merchant_id',
        'amount',
        'charge',
        'gst',
        'gst_inc_charge',
        'type',
        'transaction_id',
        'remarks',
        'utr',
        'acc_no',
        'ifsc',
        'beneficiary_name',
        'current_balance',
        'current_pending_balance',
        'current_hold_balance',
        'settlement_status',
        'pt_gateway_charges',
        'pt_agent_commission',
        'status',
        'visibility'
    ];

    public function merchant()
    {
        return $this->belongsTo(MerchantInfo::class);
    }
}
