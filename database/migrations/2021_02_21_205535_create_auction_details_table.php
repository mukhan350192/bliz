<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_details', function (Blueprint $table) {
            $table->id();
            $table->integer('auction_id');
            $table->dateTime('date_finish');
            $table->string('from_city');
            $table->string('to_city');
            $table->string('middle_city')->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->string('title');
            $table->integer('type_transport');
            $table->double('quantity')->nullable();
            $table->double('net')->nullable();
            $table->double('volume')->nullable();
            $table->double('width')->nullable();
            $table->double('length')->nullable();
            $table->double('height')->nullable();
            $table->integer('price')->nullable();
            $table->integer('currency')->nullable();
            $table->integer('payment_type')->nullable();
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
        Schema::dropIfExists('auction_details');
    }
}
