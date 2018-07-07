<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocalStoragePropToPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pictures_95319', function (Blueprint $table) {
            $table->string('local_location')->default("public/pictures/image_placeholder");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pictures_95319', function (Blueprint $table) {
            $table->dropColumn('local_location');
        });
    }
}
