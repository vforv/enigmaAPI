<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_pages_content', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('language_id');
            $table->text('title');
            $table->mediumText("description");
            $table->longText("content");
            $table->string('slug')->unique();
            $table->date("date");
            $table->foreign('page_id')->references('id')->on('flex_pages')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('flex_languages')->onDelete('cascade');
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
        Schema::dropIfExists('flex_pages_content');
    }
}
