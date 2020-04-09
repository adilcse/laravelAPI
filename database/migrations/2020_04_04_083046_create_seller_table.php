<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uid');
            $table->string('email');
            $table->string('number');
            $table->enum('current_status',array('PENDING','ACTIVE','BLOCKED'))->DEFAULT('PENDING');
            $table->bigInteger('address_id')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        Schema::table('sellers', function (Blueprint $table) {
            $table->index('uid');
            $table->foreign('address_id')->references('id')->on('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers');
    }
}
