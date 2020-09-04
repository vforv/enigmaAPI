<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlexProductAdditionalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flex_product', function (Blueprint $table) {
            $table->integer("views")->default(0);
            $table->boolean("special_offer")->default(false);
            $table->unsignedBigInteger("discount_id")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flex_product', function (Blueprint $table) {
            //
        });
    }
}
