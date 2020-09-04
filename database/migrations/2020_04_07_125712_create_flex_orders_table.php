<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlexOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("email");
            $table->string("phone");
            $table->string("address");
            $table->string("city");
            $table->integer("type");
            $table->text("note")->nullable();
            $table->unsignedBigInteger("customer_id");
            $table->foreign("customer_id")->references("id")->on("flex_customers")->onDelete("cascade");
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
        Schema::dropIfExists('flex_orders');
    }
}
