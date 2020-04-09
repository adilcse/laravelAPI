<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('status',array('ACCEPTED','OUT_FOR_DELIVERY','PENDING','CANCELLED','DELIVERED'))->default('PENDING');
            $table->enum('payment_mode',array('COD','UPI','DEBIT_CARD','CREDIT_CARD','NET_BANKING','WALLET'));
            $table->integer('total_amount')->unsigned();
            $table->integer('refund_amount')->unsigned()->default(0);
            $table->smallInteger('total_items')->unsigned();
            $table->smallInteger('rejected_items')->unsigned()->default(0);
            $table->timestamp('delivered_at')->nullable();
            $table->bigInteger('address_id')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('orders');
    }
}
