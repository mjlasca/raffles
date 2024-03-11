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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raffle_id'); 
            $table->foreign('raffle_id')->references('id')->on('raffles')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('create_user'); 
            $table->text('tickets_numbers');
            $table->integer('tickets_total');
            $table->decimal('commission');
            $table->foreign('create_user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('edit_user'); 
            $table->foreign('edit_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
