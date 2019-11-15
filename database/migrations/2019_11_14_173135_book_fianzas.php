<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookFianzas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('book_deferreds', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('book_id');
//          $table->foreign('book_id')->references('id')->on('book');
          $table->integer('cli_id')->nullable();
          $table->string('cli_email')->nullable();
          $table->string('subject')->nullable();
          $table->string('order_uuid')->nullable();
          $table->string('order_created')->nullable();
          $table->integer('amount');
          $table->integer('refunded');
          $table->integer('currency');
          $table->boolean('paid')->nullable();
          $table->string('additional');
          $table->string('service');
          $table->string('status');
          $table->string('token');
          $table->text('transactions')->nullable();
          $table->string('client_uuid');
          $table->string('key_token')->nullable();
          $table->boolean('is_deferred')->nullable()->default(0);
          $table->boolean('was_confirm')->nullable()->default(0);
          $table->float('payment')->nullable()->default(0);
          
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
