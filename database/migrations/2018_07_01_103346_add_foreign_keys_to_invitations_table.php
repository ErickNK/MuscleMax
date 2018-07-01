<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invitations_95319', function (Blueprint $table) {
            $table->foreign('inviter_id')
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
        Schema::table('invitations_95319', function (Blueprint $table) {
            $table->dropForeign('invitations_95319_inviter_id_foreign');
        });
    }
}
