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
        Schema::create('flex_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->text("description")->nullable();
            $table->integer("price")->nullable();
            $table->text("product_code")->nullable();
            $table->integer("amount")->nullable();
            $table->integer("unit");
            $table->unsignedBigInteger("category_id");
            $table->foreign("category_id")->references("id")->on("flex_product_category")->onDelete("cascade");
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
        Schema::dropIfExists('flex_product');
    }
}
