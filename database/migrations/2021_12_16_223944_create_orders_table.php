<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
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
            $table->unsignedBigInteger('order_id')->index();//unsignedBigInteger to text
            $table->string('name');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('order_status', ['WAIT_SELLER_SEND_GOODS', 'SELLER_PART_SEND_GOODS', 'PLACE_ORDER_SUCCESS',
                'WAIT_BUYER_ACCEPT_GOODS', 'FINISH', 'IN_FROZEN', 'IN_ISSUE']);
            $table->timestamp('order_date');
            $table->string('country', 4)->nullable();
            $table->string('end_reason',128)->nullable();
            $table->string('buyer_login_id',128)->nullable();
            $table->string('image',256)->nullable();
            $table->unsignedDecimal('amount')->nullable();
            $table->unsignedInteger('total_quantity')->nullable();
            $table->unsignedInteger('product_count')->nullable();
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
