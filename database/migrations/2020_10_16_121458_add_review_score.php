<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewScore extends Migration
{
    /**
      * Run the migrations.
      *
      * @return void
      */
     public function up()
     {
         Schema::table('reviews', function($table) {
            $table->boolean('up_score')->default(0);
            $table->boolean('down_score')->default(0);
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
            $table->dropColumn('up_score');
            $table->dropColumn('down_score');
         });
     }
}
