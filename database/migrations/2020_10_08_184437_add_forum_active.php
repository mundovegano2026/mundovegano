<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForumActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_boards', function($table) {
            $table->tinyInteger('active')->default(1);
        });
        Schema::table('forum_posts', function($table) {
            $table->tinyInteger('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_boards', function($table) {
            $table->dropColumn('active');
        });
        Schema::table('forum_posts', function($table) {
            $table->dropColumn('active');
        });
    }
}
