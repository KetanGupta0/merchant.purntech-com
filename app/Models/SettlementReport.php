<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettlementReport extends Model
{
    protected $table = 'settlement_reports';
    protected $primaryKey = 'srt_id';
    public $fillable = [
        'srt_merchant_id',
        'srt_business_id',
        'srt_transaction_id',
        'srt_amount',
        'srt_status',
        'srt_settlement_date',
        'srt_remarks',
        'srt_environment',
    ];
}
