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
        Schema::create('business_details', function (Blueprint $table) {
            $table->id('business_id');
            $table->bigInteger('business_merchant_id')->unsigned()->nullable(false);
            $table->string('business_name',100)->nullable(false);
            $table->string('business_type',50)->nullable(false);
            $table->string('business_address',256)->nullable(false);
            $table->string('business_website',150)->nullable(false);
            $table->enum('business_is_verified',['Verified','Not Verified'])->nullable(false)->default('Not Verified');
            $table->enum('business_status',['Active','Blocked','Deleted'])->nullable(false)->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_details');
    }
};
