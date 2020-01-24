<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('daily_prices', function(Blueprint $table)
      {
          $table->integer('id_room')->unsigned();
          $table->integer('rId_wubook')->unsigned();
          $table->date('date')->nullable();
          $table->double('price', 8, 2);

          $table->primary(['id_room', 'date']);

          $table->foreign('id_room')
                      ->references('id')
                      ->on('rooms');    
         
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
