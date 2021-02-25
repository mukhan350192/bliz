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
            $table->integer('storage_id');
            $table->integer('price');
            $table->integer('price_type');
            $table->integer('currency');
            $table->double('area')->nullable();
            $table->double('total_area')->nullable();
            $table->string('class')->nullable();
            $table->string('type_storage')->nullable();
            $table->integer('year')->nullable();
            $table->string('city_id');
            $table->string('region')->nullable();
            $table->string('address');
            $table->integer('floor')->nullable();
            $table->string('floor_type')->nullable();
            $table->string('parking_cargo')->nullable();
            $table->string('parking_car')->nullable();
            $table->string('floor_load')->nullable();

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
