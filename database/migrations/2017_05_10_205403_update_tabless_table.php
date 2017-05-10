<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Rooms', function ($table) {
            $table->foreign('sizeRoom')->references('id')->on('sizerooms');
        });
        Schema::table('seasons', function ($table) {
            $table->foreign('type')->references('id')->on('typeseasons');
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
