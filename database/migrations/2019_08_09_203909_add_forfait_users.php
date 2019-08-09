<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForfaitUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('forfaits_users', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('book_id')->unsigned()->nullable();
          $table->foreign('book_id')->references('id')->on('book');
          $table->integer('room_id')->nullable();
          $table->string('name')->nullable();
          $table->string('email')->nullable();
          $table->string('phone')->nullable();
          $table->boolean('asign')->nullable();
          $table->text('more_info');
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
      Schema::drop('forfaits_users');
    }
}
