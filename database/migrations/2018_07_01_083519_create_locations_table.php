<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations_95319', function (Blueprint $table) {
            //unique
            $table->increments('id');

            //other
            $table->string('country')->nullable();
            $table->string('county')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('landmarks')->nullable(); //will be separated by spaces (has no danger)

            //location specific
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('room')->nullable();

            /**
             * Type of the location.
             * - gym_location
             * - user_home_location
            */
            $table->double('type')->default("gym_location");

            //Geo-location
            /**
             * stored as elasticsearch geo-point "lat,long"
            */
            $table->string('latLong');

            //polymorphic relationship
            $table->morphs('locatable');

            //metadata
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
        Schema::dropIfExists('locations_95319');
    }
}
