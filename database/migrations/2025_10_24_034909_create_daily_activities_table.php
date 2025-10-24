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
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->unsignedBigInteger('sor_id')->nullable();
            $table->text('action')->nullable();
            $table->string('cust_name')->nullable();
            $table->string('pic')->nullable();
            $table->string('product')->nullable();
            $table->unsignedBigInteger('job_type_id')->nullable();
            $table->unsignedBigInteger('job_item_id')->nullable();
            $table->text('objective')->nullable();
            $table->text('result_of_issue')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold'])->default('pending');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sor_id')->references('id')->on('sors')->onDelete('set null');
            $table->foreign('job_type_id')->references('id')->on('job_types')->onDelete('set null');
            $table->foreign('job_item_id')->references('id')->on('job_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_activities');
    }
};
