<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->double('price')->default(0);
            $table->unsignedBigInteger('winning_ticket_id')->nullable();
            $table->dateTime('raffle_date')->nullable();
            $table->integer('tickets_number')->nullable();
            $table->double('ticket_commission')->default(0);
            $table->integer('raffle_status')->default(0);
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('create_user'); 
            $table->foreign('create_user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('edit_user'); 
            $table->foreign('edit_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    // ...

    // En el método down()
    public function down()
    {
        Schema::dropIfExists('raffles');
    }
};

