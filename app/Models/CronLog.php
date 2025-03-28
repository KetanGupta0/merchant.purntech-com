<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_INFO = 'info';
    protected $table = "cron_logs";
    protected $fillable = [
        'event',
        'message',
        'data',
        'status'
    ];
    protected $casts = ['data' => 'json'];
    public static function log($event, $message = null, $data = [], $status = self::STATUS_COMPLETED)
    {
        return self::create([
            'event' => $event,
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ]);
    }
}
