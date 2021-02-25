<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionAdditionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_additional', function (Blueprint $table) {
            $table->id();
            $table->integer('auction_id');
            $table->string('documents')->nullable();
            $table->string('loading')->nullable();
            $table->string('condition')->nullable();
            $table->boolean('negotiable_price')->default(false);
            $table->boolean('nds')->default(false);
            $table->boolean('when_loading')->default(false);
            $table->boolean('at_unloading')->default(false);
            $table->boolean('prepayment')->default(false);
            $table->boolean('bargain')->default(false);
            $table->boolean('price_request')->default(false);
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
        Schema::dropIfExists('auction_additional');
    }
}
