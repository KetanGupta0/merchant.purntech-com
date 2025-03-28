<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ApiToken extends Model
{
    protected $fillable = ['token', 'source_type', 'source_id', 'expires_at', 'permissions'];

    // Check if the token is expired
    public function isExpired()
    {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }
}
