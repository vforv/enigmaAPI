<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlexProductsImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("product_id");
            $table->string("image");
            $table->integer("order_number")->default(0);
            $table->foreign('product_id')->references('id')->on('flex_product')->onDelete('cascade');
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
        Schema::dropIfExists('flex_products_images');
    }
}
