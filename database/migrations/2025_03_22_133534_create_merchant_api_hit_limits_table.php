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
        Schema::create('merchant_api_hit_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->index();

            $table->unsignedInteger('payin_hits')->comment('Payin hits count')->default(0);
            $table->unsignedInteger('payin_hit_time')->comment('Minutes')->default(1);
            $table->unsignedInteger('payin_hit_limit')->comment('Hits per minute')->default(0);
            $table->unsignedInteger('payin_failed_hits')->comment('Payin failed hits')->default(0);
            $table->unsignedInteger('payin_failed_hits_limit')->comment('Payin failed hit limit')->default(0);
            $table->double('payin_failed_hits_charge', 15, 2)->comment('Payin failed hit charge')->default(0.00);

            $table->unsignedInteger('payout_hits')->comment('Payout hits count')->default(0);
            $table->unsignedInteger('payout_hit_time')->comment('Minutes')->default(1);
            $table->unsignedInteger('payout_hit_limit')->comment('Payout hits per minute')->default(0);
            $table->unsignedInteger('payout_failed_hits')->comment('Payout failed hits count')->default(0);
            $table->unsignedInteger('payout_failed_hits_limit')->comment('Payout failed hit limit')->default(0);
            $table->double('payout_failed_hits_charge', 15, 2)->comment('Payout failed hit charge')->default(0.00);

            $table->unsignedInteger('balance_check_hits')->comment('Balance check hits count')->default(0);
            $table->unsignedInteger('balance_check_hits_time')->comment('Minutes')->default(1);
            $table->unsignedInteger('balance_check_hits_limit')->comment('Balance check hit limit')->default(0);

            $table->unsignedInteger('transaction_check_hits')->comment('Transaction check hits count')->default(0);
            $table->unsignedInteger('transaction_check_hits_time')->comment('Minutes')->default(1);
            $table->unsignedInteger('transaction_check_hits_limit')->comment('Transaction check hit limit')->default(0);

            $table->unsignedInteger('webhook_hits')->comment('Webhook hits count')->default(0);
            $table->unsignedInteger('webhook_hits_time')->comment('Minutes')->default(1);
            $table->unsignedInteger('webhook_hits_limit')->comment('Webhook hit limit')->default(0);

            $table->unsignedInteger('callback_hits')->comment('Callback hits count')->default(0);
            $table->unsignedInteger('callback_hits_time')->comment('Minutes')->default(1);
            $table->unsignedInteger('callback_hits_limit')->comment('Callback hit limit')->default(0);

            $table->unsignedInteger('overall_hit_limit')->comment('Total API hits limit')->default(0);

            $table->enum('status',['active','not active'])->default('not active');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchant_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_api_hit_limits');
    }
};
