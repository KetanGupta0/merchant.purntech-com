<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KYCDocument extends Model
{
    protected $table = 'k_y_c_documents';
    protected $primaryKey = 'kyc_id';

    public $fillable = [
        'kyc_merchant_id',
        'kyc_business_id',
        'kyc_document_name',
        'kyc_document_path',
        'kyc_is_verified',
        'kyc_status',
    ];
}
