<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('occupation');
            $table->decimal('priceHigh', 8, 2);
            $table->decimal('priceMed', 8, 2);
            $table->decimal('priceLow', 8, 2);
            $table->decimal('costHigh', 8, 2);
            $table->decimal('costMed', 8, 2);
            $table->decimal('costLow', 8, 2);
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
