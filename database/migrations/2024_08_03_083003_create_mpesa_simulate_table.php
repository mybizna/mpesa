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
        Schema::create('mpesa_simulate', function (Blueprint $table) {
            $table->id();

            $table->string('amount');
            $table->string('phone');
            $table->string('reference')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('gateway_id')->constrained('mpesa_gateway')->onDelete('cascade')->nullable()->index('gateway_id');
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
        Schema::dropIfExists('mpesa_simulate');
    }
};
