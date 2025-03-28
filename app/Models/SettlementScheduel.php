<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettlementScheduel extends Model
{
    protected $table = "settlement_scheduels";
    protected $fillable = [
        'name',
        'type',
        'transaction_start_time',
        'transaction_end_time',
        'settlement_start_time',
        'settlement_end_time',
        'settlement_delay_hours',
        'settlement_delay_days',
        'status',
    ];
}
