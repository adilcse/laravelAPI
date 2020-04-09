<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id')->unsigned();
            $table->string('name');
            $table->longText('description');
            $table->string('image');
            $table->integer('price')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('catagory_id')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->index('seller_id');
            $table->foreign('catagory_id')->references('id')->on('catagories');
            $table->foreign('seller_id')->references('id')->on('sellers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
