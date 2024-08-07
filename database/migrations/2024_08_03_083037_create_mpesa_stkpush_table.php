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
        Schema::create('mpesa_stkpush', function (Blueprint $table) {
            $table->id();

            $table->string('amount');
            $table->string('phone');
            $table->string('reference');
            $table->string('description')->nullable();
            $table->string('command')->nullable();
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->foreignId('gateway_id')->nullable();
            $table->tinyInteger('completed')->nullable()->default(0);
            $table->tinyInteger('successful')->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_stkpush');
    }
};
