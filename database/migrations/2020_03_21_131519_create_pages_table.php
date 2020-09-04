<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flex_pages_content', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->foreign("category_id")->references("id")->on("flex_pages_category")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flex_pages', function (Blueprint $table) {
            //
        });
    }
}
