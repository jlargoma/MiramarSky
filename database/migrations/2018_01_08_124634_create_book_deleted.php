<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookDeleted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_deleted', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->date('start');
            $table->date('finish');
            $table->string('comment');
            $table->string('book_comments');
            $table->integer('type_book');
            $table->integer('pax');
            $table->integer('nigths');
            $table->integer('agency');
            $table->decimal('PVPAgencia',8,2);

            $table->decimal('sup_limp',8,2);
            $table->decimal('cost_limp',8,2);
            $table->decimal('sup_park',8,2);
            $table->integer('type_park');
            $table->decimal('cost_park',8,2);
            $table->integer('type_luxury');

            $table->decimal('sup_lujo',8,2);
            $table->decimal('cost_lujo',8,2);
            $table->decimal('cost_apto',8,2);
            $table->decimal('cost_total',8,2);
            $table->decimal('total_price',8,2);
            $table->decimal('total_ben',8,2);
            $table->decimal('extra',8,2);
            $table->decimal('extraPrice',8,2);
            $table->decimal('extraCost',8,2);
            $table->decimal('inc_percent',4,2);
            $table->decimal('ben_jorge',8,2);
            $table->decimal('ben_jaime',8,2);
            $table->integer('send');
            $table->integer('statusCobro');
            $table->decimal('real_price',8,2);

            $table->integer('real_pax');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('room_id')->references('id')->on('rooms');

            $table->integer('schedule');
            $table->integer('scheduleOut')->default(12);
            $table->string('book_owned_comments');
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
        Schema::drop('book_deleted');
    }
}
