<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('post_id');
            $table->string('from');
            $table->string('middle_city')->nullable();
            $table->string('to');
            $table->integer('type_transport');
            $table->float('volume')->nullable();
            $table->float('net')->nullable();
            $table->string('start_date');
            $table->string('end_date');
            $table->integer('quantity')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('length')->nullable();
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
        Schema::dropIfExists('details');
    }
}
