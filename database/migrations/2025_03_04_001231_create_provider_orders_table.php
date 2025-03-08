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
        Schema::create('provider_orders', function (Blueprint $table) {
            $table->id();
            $table->string('provider_id', 128);
            $table->string('provider_status', 16);
            $table->decimal('provider_cut', 5, 2)->nullable();
            $table->decimal('provider_gross', 9, 2)->nullable();
            $table->decimal('provider_net', 9, 2)->nullable();
            $table->string('provider_payment_id', 128)->nullable();
            $table->string('payer_id', 32)->nullable();
            $table->string('payer_email', 128)->nullable();
            $table->string('payer_name', 128)->nullable();
            $table->string('payer_last_name', 128)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_orders');
    }
};
