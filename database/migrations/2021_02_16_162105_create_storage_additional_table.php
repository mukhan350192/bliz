<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageAdditionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_additional', function (Blueprint $table) {
            $table->id();
            $table->integer('storage_id');
            $table->string('fire_system')->nullable();
            $table->string('ventilation')->nullable();
            $table->boolean('fire_alarm');
            $table->boolean('security_alarm');
            $table->boolean('security_area_transport');
            $table->boolean('inline_blocks');
            $table->boolean('rack');
            $table->boolean('ramp');
            $table->text('infrastructure')->nullable();
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
        Schema::dropIfExists('storage_additional');
    }
}
