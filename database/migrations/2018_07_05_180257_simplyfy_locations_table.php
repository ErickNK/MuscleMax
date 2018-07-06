<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimplyfyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations_95319', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('county');
            $table->dropColumn('zip_code');
            $table->dropColumn('city');
            $table->dropColumn('street');
            $table->dropColumn('landmarks');
            $table->dropColumn('building');
            $table->dropColumn('floor');
            $table->dropColumn('room');

            $table->string("address")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations_95319', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('county')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('landmarks')->nullable(); //will be separated by spaces (has no danger)
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('room')->nullable();

            $table->dropColumn("address");
        });
    }
}
