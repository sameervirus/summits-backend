<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('payment_gateway');            
            $table->string('notes')->nullable();
            $table->string('total');
            $table->string('shipping_fee');
            $table->unsignedBigInteger('user_id');
            $table->string('fname');
            $table->string('lname');
            $table->string('email');
            $table->string('phone');
            $table->unsignedBigInteger('address_id');
            $table->string('address');
            $table->unsignedBigInteger('status_id');
            $table->string('tracking_number')->nullable();
            $table->string('paymob_id')->nullable();
            $table->string('paymob_order')->nullable();
            $table->boolean('paymob_pending')->nullable();
            $table->boolean('paymob_success')->nullable();
            $table->string('paymob_amount')->nullable();
            $table->string('discount')->nullable();
            $table->string('coupon')->nullable();
            $table->dateTime('delivery_time')->nullable();
            $table->timestamps();
        });
        Schema::create('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->string('quantity');
            $table->string('price');
        });
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('english_name');
            $table->string('arabic_name');
            $table->string('color');
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
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('status');
    }
};
