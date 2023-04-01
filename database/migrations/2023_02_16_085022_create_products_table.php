<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name_english');
            $table->string('name_arabic');
            $table->text('description_english');
            $table->text('description_arabic');
            $table->bigInteger('quantity');
            $table->string('price')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('unit')->nullable();
            $table->string('weight')->nullable();
            $table->string('product_type')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE products AUTO_INCREMENT = 10000;");

        Schema::create('category_product', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
        });

        Schema::create('product_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
        });

        Schema::create('application_product', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('product_id');
        });

        Schema::create('product_user', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
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
};
