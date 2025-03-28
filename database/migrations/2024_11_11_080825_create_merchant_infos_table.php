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
        Schema::create('merchant_infos', function (Blueprint $table) {
            $table->id('merchant_id');
            $table->string('merchant_name',100)->nullable(false);
            $table->string('merchant_phone',15)->unique()->nullable(false);
            $table->string('merchant_phone2',15)->nullable(true);
            $table->string('merchant_email',150)->unique()->nullable(false);
            $table->string('merchant_aadhar_no',12)->unique()->nullable(false);
            $table->string('merchant_pan_no',10)->unique()->nullable(false);
            $table->string('merchant_password')->nullable(false);
            $table->string('merchant_plain_password')->nullable(false);
            $table->enum('merchant_is_onboarded',['Yes','No'])->nullable(false)->default('No');
            $table->enum('merchant_is_verified',['Verified','Not Verified'])->nullable(false)->default('Not Verified');
            $table->enum('merchant_status',['Active','Blocked','Deleted'])->nullable(false)->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_infos');
    }
};
