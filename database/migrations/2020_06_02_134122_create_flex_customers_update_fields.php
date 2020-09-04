<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlexCustomersUpdateFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flex_customers', function (Blueprint $table) {
            $table->string("company_name");
            $table->string("pib");
            $table->string("pdv");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flex_customers', function (Blueprint $table) {
            //
        });
    }
}

