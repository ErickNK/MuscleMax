<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes_95319', function (Blueprint $table) {
            $table->foreign('weight_measurement_id')
                ->references('id')->on('weight_measurements_95319')
                ->onDelete('cascade');

            $table->foreign('owner_id')
                ->references('id')->on('users_95319')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes_95319', function (Blueprint $table) {
            $table->dropForeign('notes_95319_weight_measurement_id_foreign');
            $table->dropForeign('notes_95319_owner_id_foreign');

        });
    }
}
