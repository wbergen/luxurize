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
        Schema::create('obligations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obligation_status_id');
            $table->foreignId('user_id');
            $table->foreignId('order_id');
            $table->timestamps();
        });

        Schema::create('obligables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obligation_id')->constrained();
            $table->morphs('obligable'); // obligable_id, obligable_type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obligations');
    }
};
