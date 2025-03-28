<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'log_id';
    public $fillable = [
        'log_user_id',
        'log_user_type',
        'log_event_type',
        'log_description',
        'log_ip_address',
        'log_user_agent'
    ];
}
