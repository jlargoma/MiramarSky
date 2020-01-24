<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsWubook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('wubook_queues', function(Blueprint $table)
      {
          $table->integer('id')->unsigned();
          $table->integer('id_room');
          $table->integer('rId_wubook')->nullable();
          $table->date('date_start')->nullable();
          $table->date('date_end')->nullable();
          $table->boolean('avail');
          $table->boolean('sent');
          $table->primary('id');

         
      });
      
      Schema::table('book', function (Blueprint $table) {
         $table->integer('rId_wubook')->nullable();
      });
      
      Schema::table('customers', function (Blueprint $table) {
         $table->string('surname')->nullable();
         $table->string('language')->nullable();
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
