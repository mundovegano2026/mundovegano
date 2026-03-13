<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserForum extends Migration
{
    /**
      * Run the migrations.
      *
      * @return void
      */
      public function up()
      {
        Schema::table('forum_posts', function($table) {
           $table->integer('user_id');
        });
        Schema::table('forum_boards', function($table) {
           $table->integer('user_id');
        });
      }
  
      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
        Schema::table('forum_posts', function($table) {
           $table->dropColumn('user_id');
        });
        Schema::table('forum_boards', function($table) {
           $table->dropColumn('user_id');
        });
      }
}
