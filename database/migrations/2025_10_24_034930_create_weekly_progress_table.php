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
        Schema::create('weekly_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('year');
            $table->integer('week_number');
            $table->text('last_week_status')->nullable();
            $table->text('p1')->nullable(); // Priority 1: Fokus harus selesai within week
            $table->text('p2')->nullable(); // Priority 2: Sekunder, boleh within week tapi less priority
            $table->text('p3')->nullable(); // Priority 3: Kalau ada waktu - target delivery masih 2+ week ahead
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'year', 'week_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_progress');
    }
};
