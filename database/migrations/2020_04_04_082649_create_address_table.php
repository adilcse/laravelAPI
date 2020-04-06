<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('number',10)->nullable();
            $table->string('city')->nullable();
            $table->string('landmark')->nullable();
            $table->string('locality')->nullable();
            $table->string('pin')->nullable();
            $table->bigInteger('state_id')->unsigned()->nullable();
            $table->double('lat',20,16)->nullable();
            $table->double('lng',20,16)->nullable();
            $table->enum('address_type',array('USER','SELLER'))->default('USER');
            $table->timestamps();
        });
        Schema::table('address', function (Blueprint $table) {
            $table->foreign('state_id')->references('id')->on('indian_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
