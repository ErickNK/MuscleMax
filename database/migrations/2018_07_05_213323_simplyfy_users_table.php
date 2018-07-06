<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimplyfyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_95319', function (Blueprint $table) {
            $table->dropColumn('bio');
            $table->string('gender')->default("Male");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_95319', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->string('bio')->nullable();
        });
    }
}
