<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('products', function($table) {
            $table->integer('brand_id');
            $table->integer('review_id');
            $table->integer('tag_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('products', function($table) {
            $table->dropColumn('brand_id');
            $table->dropColumn('review_id');
            $table->dropColumn('tag_id');
        });
    }
}
