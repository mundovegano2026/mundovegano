<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacityUnitProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacity_units', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::table('products', function($table) {
            $table->decimal('capacity');
            $table->integer('capacity_unit_id');
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
            $table->dropColumn('capacity');
            $table->dropColumn('capacity_unit_id');
        });

        Schema::dropIfExists('capacity_units');

    }
}
