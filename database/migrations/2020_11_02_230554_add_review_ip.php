<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewIp extends Migration
{
    /**
      * Run the migrations.
      *
      * @return void
      */
      public function up()
      {
        Schema::table('reviews', function($table) {
           $table->text('ip');
        });
      }
  
      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
        Schema::table('reviews', function($table) {
           $table->dropColumn('ip');
        });
      }
}
