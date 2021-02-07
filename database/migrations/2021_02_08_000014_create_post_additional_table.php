<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostAdditionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_additional', function (Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->string('documents')->nullable();
            $table->string('loading')->nullable();
            $table->string('condition')->nullable();
            $table->string('addition')->nullable();
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
        Schema::dropIfExists('post_additional');
    }
}
