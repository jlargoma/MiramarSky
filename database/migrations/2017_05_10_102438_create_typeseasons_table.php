<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeseasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typeseasons', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name');
            $table->timestamps();
        });

        Schema::table('seasons', function ($table) {
            $table->foreign('type')->references('id')->on('typeseasons');
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
