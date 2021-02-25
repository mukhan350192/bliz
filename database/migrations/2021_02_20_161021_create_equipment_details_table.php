<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('equipment_id');
            $table->integer('type_equipment');
            $table->string('name');
            $table->string('city_id');
            $table->string('region')->nullable();
            $table->string('address');
            $table->integer('net')->nullable();
            $table->integer('year')->nullable();
            $table->integer('type_blade')->nullable();
            $table->integer('power')->nullable();
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('rise')->nullable();
            $table->double('deep')->nullable();
            $table->text('description')->nullable();
            $table->integer('price');
            $table->integer('price_type');
            $table->integer('currency');
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
        Schema::dropIfExists('equipment_details');
    }
}
