<?php

namespace App\Services;

use App\Models\ApiLog;
use Illuminate\Support\Facades\Session;

class ApiLoggerService
{
    /**
     * Logs an API event.
     *
     * @param string      $endpoint       The API endpoint being accessed.
     * @param string      $event          A short description of the event.
     * @param string      $message        A detailed message regarding the event.
     * @param array|null  $requestPayload The payload from the request.
     * @param array|null  $response       The response data (if any).
     * @param string      $status         The status of the event ('info', 'error', 'exception').
     */
    public static function logEvent($endpoint, $event, $message, $requestPayload = null, $response = null, $status = 'info')
    {
        // Get the authenticated user ID if available
        $userId = Session::has('userId') ? Session::get('userId') : null;

        ApiLog::create([
            'user_id'         => $userId,
            'endpoint'        => $endpoint,
            'event'           => $event,
            'message'         => $message,
            'request_payload' => $requestPayload,
            'response'        => $response,
            'status'          => $status,
        ]);
    }
}
