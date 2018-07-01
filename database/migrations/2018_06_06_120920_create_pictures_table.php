<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures_95319', function (Blueprint $table) {
            //Unique
            $table->increments('id');

            $table->string('name');
            $table->string('type');
            $table->unsignedInteger('size');
            $table->string('description')->nullable();
            $table->string('remote_location');

            //polymorphic relationship
            $table->morphs('picturable');

            //Record Metadata fields
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
        Schema::dropIfExists('pictures_95319');
    }
}
