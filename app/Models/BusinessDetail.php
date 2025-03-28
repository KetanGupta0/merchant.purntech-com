<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDetail extends Model
{
    protected $table = 'business_details';
    protected $primaryKey = 'business_id';

    public $fillable = [
        'business_merchant_id',
        'business_name',
        'business_type',
        'business_address',
        'business_website',
        'business_is_verified',
        'business_status',
    ];
}
