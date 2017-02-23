<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nameRoom');
        });

        Schema::table('rooms', function ($table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('priceHigh', 8, 2);
            $table->decimal('priceMed', 8, 2);
            $table->decimal('priceLow', 8, 2);
            $table->decimal('costHigh', 8, 2);
            $table->decimal('costMed', 8, 2);
            $table->decimal('costLow', 8, 2);
            $table->integer('typeApto')->default(0);
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
        //
    }
}
