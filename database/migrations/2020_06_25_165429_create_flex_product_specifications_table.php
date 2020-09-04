<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlexProductSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_product_specifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("product_id");
            $table->string("label");
            $table->string("value");
            $table->foreign("product_id")->references("id")->on("flex_product")->onDelete("cascade");
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
        Schema::dropIfExists('flex_product_specifications');
    }
}
