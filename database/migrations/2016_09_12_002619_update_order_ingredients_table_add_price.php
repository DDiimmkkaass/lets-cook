<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderIngredientsTableAddPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'order_ingredients',
            function (Blueprint $table) {
                $table->integer('price')->unsigned()->after('name');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'order_ingredients',
            function (Blueprint $table) {
                $table->dropColumn('price');
            }
        );
    }
}
