<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeightMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_measurements_95319', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id');

            $table->float('weight');
            $table->float('height');
            $table->float('bmi');
            /**
             * Wether this was the initial weight measurement
            */
            $table->string('type');

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
        Schema::dropIfExists('weight_measurements_95319');
    }
}
