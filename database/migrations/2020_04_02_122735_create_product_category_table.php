<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_product_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer("order")->default(0);
            $table->integer("level")->default(0);
            $table->text("description")->nullable();
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->foreign("parent_id")->references("id")->on("flex_product_category")->onDelete("cascade");
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
        Schema::dropIfExists('flex_product_category');
    }
}
