<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fly_from')->unsigned();
            $table->bigInteger('fly_to')->unsigned();
            $table->timestamps();
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->foreign('fly_from')->references('id')->on('directions')->onDelete('cascade');
            $table->foreign('fly_to')->references('id')->on('directions')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
