<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'user_id',
        'endpoint',
        'event',
        'message',
        'request_payload',
        'response',
        'status',
    ];

    protected $casts = [
        'request_payload' => 'json',
        'response' => 'json',
    ];
}
