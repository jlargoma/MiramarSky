<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForfaitItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('forfaits_items', function (Blueprint $table) {
          $table->string('id');
          $table->string('cat');
          $table->string('name');
          $table->string('type');
          $table->string('equip');
          $table->string('class');
          $table->integer('guestNumber')->nullable();
          $table->boolean('status')->nullable();
          $table->text('log_data');
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
      Schema::drop('forfaits_items');
    }
}
