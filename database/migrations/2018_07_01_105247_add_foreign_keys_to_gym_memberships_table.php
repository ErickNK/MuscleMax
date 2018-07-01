<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToGymMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_memberships_95319', function (Blueprint $table) {
            $table->foreign('gym_id')
                ->references('id')->on('gyms_95319')
                ->onDelete('cascade');

            $table->foreign('user_id')
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
        Schema::table('gym_memberships_95319', function (Blueprint $table) {
            $table->dropForeign('gym_memberships_95319_gym_id_foreign');
            $table->dropForeign('gym_memberships_95319_user_id_foreign');
        });
    }
}
