<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlexOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_orders_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("order_id");
            $table->integer("amount");
            $table->foreign("product_id")->references("id")->on("flex_product")->onDelete("cascade");
            $table->foreign("order_id")->references("id")->on("flex_orders")->onDelete("cascade");
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
        Schema::dropIfExists('flex_orders_items');
    }
}
