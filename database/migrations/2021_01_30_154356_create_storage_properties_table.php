<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoragePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_properties', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->integer('rentTypeID');
            $table->double('area')->nullable();
            $table->double('total_area')->nullable();
            $table->string('class')->nullable();
            $table->string('type_storage')->nullable();
            $table->integer('year')->nullable();
            $table->integer('city_id');
            $table->string('address');
            $table->integer('floor')->nullable();
            $table->string('floor_type')->nullable();
            $table->string('warning')->nullable();
            $table->string('warning_area')->nullable();
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
        Schema::table('storage_properties', function (Blueprint $table) {
            Schema::dropIfExists('storage_properties');
        });
    }
}
