<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTaggedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagged_95319', function (Blueprint $table) {
            $table->foreign('tag_id')
                ->references('id')->on('tags_95319')
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
        Schema::table('tagged_95319', function (Blueprint $table) {
            $table->dropForeign('tagged_95319_tag_id_foreign');
        });
    }
}
