<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('k_y_c_documents', function (Blueprint $table) {
            $table->id('kyc_id');
            $table->bigInteger('kyc_merchant_id')->unsigned()->nullable(false);
            $table->bigInteger('kyc_business_id')->unsigned()->nullable(false);
            $table->string('kyc_document_name')->nullable(false);
            $table->string('kyc_document_path')->nullable(false);
            $table->string('kyc_document_type',50)->nullable(true);
            $table->enum('kyc_is_verified',['Verified','Not Verified'])->nullable(false)->default('Not Verified');
            $table->enum('kyc_status',['Active','Deleted'])->nullable(false)->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k_y_c_documents');
    }
};
