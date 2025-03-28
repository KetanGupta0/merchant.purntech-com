<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function generateNewToken(Request $request){
        $validated = $request->validate([
            'source_type' => 'required|string', // 'user', 'merchant', etc.
            'source_id' => 'required|string',
            'permissions' => 'nullable|string', // Optional permissions
            'expires_in_minutes' => 'nullable|integer' // Expiration in minutes
        ]);
        $existingToken = ApiToken::whereRaw('BINARY source_type = ?', [$validated['source_type']])
            ->whereRaw('BINARY source_id = ?', [$validated['source_id']])
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
            })
            ->first();
        if ($existingToken) {
            return response()->json(['token' => $existingToken->token, 'expires_at' => $existingToken->expires_at], 200);
        }
        // Generate a new token
        $token = Str::random(60);
        $expiresAt = isset($validated['expires_in_minutes'])
            ? Carbon::now()->addMinutes($validated['expires_in_minutes'])
            : null; // Null means no expiry
        ApiToken::create([
            'token' => $token,
            'source_type' => $validated['source_type'],
            'source_id' => $validated['source_id'],
            'permissions' => $validated['permissions'] ?? 'read', // Default to 'read' access
            'expires_at' => $expiresAt,
        ]);
        return response()->json(['token' => $token, 'expires_at' => $expiresAt], 201);
    }

    public function fetchUserData(Request $request){
        $apiToken = $request->attributes->get('api_token');
        return response()->json([
            'message' => 'Authenticated user',
            'permissions' => $apiToken->permissions,
            'expires_at' => $apiToken->expires_at
        ]);
    }

    public function regenerateUserToken(Request $request){
        $validated = $request->validate([
            'source_type' => 'required|string',
            'source_id' => 'required|string',
            'permissions' => 'nullable|string',
            'expires_in_minutes' => 'nullable|integer'
        ]);
        $existingToken = ApiToken::whereRaw('BINARY source_type = ?', [$validated['source_type']])
            ->whereRaw('BINARY source_id = ?', [$validated['source_id']])
            ->first();
        if (!$existingToken) {
            return response()->json(['message' => 'No existing token found'], 404);
        }
        // Generate a new token
        $newToken = Str::random(60);
        $expiresAt = isset($validated['expires_in_minutes'])
            ? Carbon::now()->addMinutes($validated['expires_in_minutes'])
            : null;
        $existingToken->update([
            'token' => $newToken,
            'expires_at' => $expiresAt,
            'permissions' => $validated['permissions'] ?? 'read'
        ]);
        return response()->json(['token' => $newToken, 'expires_at' => $expiresAt], 200);
    }
}
