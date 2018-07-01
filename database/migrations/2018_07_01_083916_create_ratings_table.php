<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings_95319', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');

            //max 5 stars
            $table->unsignedInteger('stars')->default(0);

            //polymorphic relationship
            $table->morphs('ratable');

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
        Schema::dropIfExists('ratings_95319');
    }
}
