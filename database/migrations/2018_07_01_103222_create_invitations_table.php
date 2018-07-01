<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations_95319', function (Blueprint $table) {
            //unique
            $table->increments('id');
            $table->string('inviteCode');

            $table->unsignedInteger('inviter_id');

            $table->string('invitee_name')->nullable();
            $table->string('invitee_email')->nullable();
            $table->string('invitee_tel')->nullable();

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
        Schema::dropIfExists('invitations_95319');
    }
}
