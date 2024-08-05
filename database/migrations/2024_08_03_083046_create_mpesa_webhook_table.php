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
        Schema::create('mpesa_webhook', function (Blueprint $table) {
            $table->id();

            $table->string('slug');
            $table->string('validation_url');
            $table->string('confirmation_url');
            $table->string('paybill_till');
            $table->string('shortcode');
            $table->tinyInteger('published')->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_webhook');
    }
};
