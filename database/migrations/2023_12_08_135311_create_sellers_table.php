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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('lastname',100);
            $table->string('phone',20);
            $table->string('email',20)->nullable();
            $table->string('address',100);
            $table->unsignedBigInteger('create_user'); 
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
        Schema::dropIfExists('sellers');
    }
};
