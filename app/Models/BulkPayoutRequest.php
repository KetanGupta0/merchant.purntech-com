<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkPayoutRequest extends Model
{
    protected $table = "bulk_payout_requests";
    protected $fillable = [
        "merchant_id",
        "file_path",
        "total_payouts",
        "remark",
        "status",
    ];
}
