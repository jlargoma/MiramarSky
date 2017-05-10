<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTyperoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typerooms', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name');
            $table->timestamps();
        });

        Schema::table('rooms', function ($table) {            
            $table->foreign('owned')->references('id')->on('typerooms');
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
