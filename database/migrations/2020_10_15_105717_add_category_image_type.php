<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryImageType extends Migration
{
    /**
      * Run the migrations.
      *
      * @return void
      */
     public function up()
     {
         Schema::table('category_images', function($table) {
             $table->integer('type_id');
         });
     }
 
     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::table('category_images', function($table) {
             $table->dropColumn('type_id');
         });
     }
}
