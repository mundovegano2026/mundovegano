<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStoreColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function($table) {
            $table->dropColumn('geom');
            $table->dropColumn('distrito_id');
            $table->dropColumn('concelho_id');
            $table->dropColumn('freguesia_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function($table) {
            $table->point('geom');
            $table->integer('distrito_id');
            $table->integer('concelho_id');
            $table->integer('freguesia_id');
        });
    }
}
