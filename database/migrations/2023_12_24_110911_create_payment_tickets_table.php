<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id'); 
            $table->foreign('delivery_id')->references('id')->on('deliveries');
            $table->unsignedBigInteger('create_user'); 
            $table->foreign('create_user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('edit_user'); 
            $table->foreign('edit_user')->references('id')->on('users')->onDelete('cascade');
            $table->double('payment_value')->nullable();
            $table->integer('status')->default(0);
            $table->text('detail')->nullable();
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
        Schema::dropIfExists('payment_tickets');
    }
}
