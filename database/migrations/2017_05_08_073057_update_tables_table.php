<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('book', function ($table) {
           $table->foreign('user_id')->references('id')->on('users');
           $table->foreign('customer_id')->references('id')->on('customers');
           $table->foreign('room_id')->references('id')->on('rooms');
           $table->foreign('season_id')->references('id')->on('seasons');
        });

        Schema::table('paymentspro', function ($table) {
           $table->foreign('room_id')->references('id')->on('rooms');
        });

        Schema::table('payments', function ($table) {
           $table->foreign('book_id')->references('id')->on('book');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
