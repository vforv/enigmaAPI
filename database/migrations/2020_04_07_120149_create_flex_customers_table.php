<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlexCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("email");
            $table->string("password");
            $table->string("phone");
            $table->string("address");
            $table->string("city");
            $table->integer("type");
            $table->boolean("active")->default(false);
            $table->string("token")->nullable();
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
        Schema::dropIfExists('flex_customers');
    }
}
