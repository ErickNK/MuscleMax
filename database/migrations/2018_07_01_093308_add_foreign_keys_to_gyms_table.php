<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gyms_95319', function (Blueprint $table) {
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
        Schema::table('gyms_95319', function (Blueprint $table) {
            $table->dropForeign('gyms_95319_owner_id_foreign');
        });
    }
}
