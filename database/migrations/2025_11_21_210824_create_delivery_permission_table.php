<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_requests')->nullable();
            $table->foreign('user_requests')->references('id')->on('users');
            $table->unsignedBigInteger('allow_user')->nullable();
            $table->foreign('allow_user')->references('id')->on('users');
            $table->boolean('status')->default(true);
            $table->string("modification");
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->foreign('delivery_id')->references('id')->on('deliveries');
            $table->date('date_permission');
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
        Schema::dropIfExists('delivery_permissions');
    }
}
