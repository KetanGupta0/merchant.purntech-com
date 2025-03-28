<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlWhiteListing extends Model
{
    protected $table = 'url_white_listings';
    protected $primaryKey = 'uwl_id';
    public $fillable = [
        'uwl_merchant_id',
        'uwl_url',
        'uwl_ip_address',
        'uwl_status',
        'uwl_environment',
    ];
}
