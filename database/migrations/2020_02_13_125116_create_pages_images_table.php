<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_pages_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("page_id");
            $table->string("image");
            $table->integer("order_number")->default(0);
            $table->foreign('page_id')->references('id')->on('flex_pages')->onDelete('cascade');
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
        Schema::dropIfExists('flex_pages_images');
    }
}
