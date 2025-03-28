<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'agent_wallet_transactions';
    protected $fillable = [
        'agent_id',
        'amount',
        'charge',
        'transaction_id',
        'order_id',
        'remarks',
        'utr',
        'bank_name',
        'acc_no',
        'ifsc',
        'beneficiary_name',
        'current_balance',
        'current_pending_balance',
        'current_hold_balance',
        'pt_gateway_charges',
        'payload',
        'response',
        'status',
        'visibility'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
