<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raffle_id'); 
            $table->foreign('raffle_id')->references('id')->on('raffles')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('create_user'); 
            $table->foreign('create_user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('edit_user'); 
            $table->foreign('edit_user')->references('id')->on('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->double('total')->nullable();
            $table->double('used')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
