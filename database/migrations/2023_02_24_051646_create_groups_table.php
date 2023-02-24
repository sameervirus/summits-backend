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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('title_english');
            $table->string('title_arabic');
            $table->text('description_english');
            $table->text('description_arabic');
            $table->timestamps();
        });

        Schema::create('group_product', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
        Schema::dropIfExists('group_product');
    }
};
