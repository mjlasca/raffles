<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToOptimizeQueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Índices para la tabla raffles
        Schema::table('raffles', function (Blueprint $table) {
            $table->index('raffle_date', 'idx_raffle_date');
        });

        // Índices para la tabla tickets
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('raffle_id', 'idx_ticket_raffle_id');
            $table->index('user_id', 'idx_ticket_user_id');
            $table->index('payment_commission', 'idx_ticket_payment_commission');
            $table->index('payment', 'idx_ticket_payment');
            $table->index('price', 'idx_ticket_price');
        });

        // Índices para la tabla deliveries
        Schema::table('deliveries', function (Blueprint $table) {
            $table->index('raffle_id', 'idx_delivery_raffle_id');
            $table->index('user_id', 'idx_delivery_user_id');
            $table->index('total', 'idx_delivery_total');
            $table->index('used', 'idx_delivery_used');
        });

        // Índices para la tabla assignments
        Schema::table('assignments', function (Blueprint $table) {
            $table->index('id', 'idx_assignment_id');
            $table->index('commission', 'idx_assignment_commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar los índices en caso de rollback
        Schema::table('raffles', function (Blueprint $table) {
            $table->dropIndex('idx_raffle_date');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('idx_ticket_raffle_id');
            $table->dropIndex('idx_ticket_user_id');
            $table->dropIndex('idx_ticket_payment_commission');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropIndex('idx_delivery_raffle_id');
            $table->dropIndex('idx_delivery_user_id');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropIndex('idx_assignment_id');
        });
    }
}
