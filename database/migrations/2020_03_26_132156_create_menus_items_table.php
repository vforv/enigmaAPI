<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_menus_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("parent_id")->default(0);
            $table->string("name");
            $table->unsignedInteger("order");
            $table->unsignedInteger("level");
            $table->string("link")->nullable();
            $table->string("file")->nullable();
            $table->boolean("external")->default(false);
            $table->boolean("placeholder")->default(false);
            $table->unsignedBigInteger("menu_id");
            $table->unsignedBigInteger("language_id");
            $table->foreign("menu_id")->references("id")->on("flex_menus")->onDelete("cascade");
            $table->foreign("language_id")->references("id")->on("flex_languages")->onDelete("cascade");
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
        Schema::dropIfExists('menus_items');
    }
}
