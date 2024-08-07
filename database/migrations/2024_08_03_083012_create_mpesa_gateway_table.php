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
        Schema::create('mpesa_gateway', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug');
            $table->string('consumer_key');
            $table->string('consumer_secret');
            $table->string('initiator_name');
            $table->string('initiator_password');
            $table->string('passkey');
            $table->string('party_a');
            $table->string('party_b');
            $table->string('business_shortcode');
            $table->string('phone_number')->nullable();
            $table->enum('type', ['paybill', 'till_number', 'shortcode'])->default('paybill')->nullable();
            $table->enum('method', ['sending', 'receiving'])->default('sending')->nullable();
            $table->foreignId('ledger_id')->nullable();
            $table->foreignId('currency_id')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('default')->nullable()->default(0);
            $table->tinyInteger('sandbox')->nullable()->default(0);
            $table->tinyInteger('published')->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_gateway');
    }
};
