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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('position');
            $table->string('slug');
            $table->string('title_english');
            $table->string('title_arabic');
            $table->string('description_english')->nullable();
            $table->string('description_arabic')->nullable();
            $table->string('btnText_english')->nullable();
            $table->string('btnText_arabic')->nullable();
            $table->string('type')->nullable();
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('banners');
    }
};
