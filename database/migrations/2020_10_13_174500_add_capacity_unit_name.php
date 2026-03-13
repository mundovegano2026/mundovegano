<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacityUnitName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('capacity_units', function($table) {
            $table->string('name', 50);
            $table->string('symbol', 10);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('capacity_units', function($table) {
            $table->dropColumn('name');
            $table->dropColumn('symbol');
        });

    }
}
