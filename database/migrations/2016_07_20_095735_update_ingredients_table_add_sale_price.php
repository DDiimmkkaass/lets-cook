<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIngredientsTableAddSalePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'ingredients',
            function (Blueprint $table) {
                $table->integer('sale_price')->unsigned()->after('price');
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
            'ingredients',
            function (Blueprint $table) {
                $table->dropColumn('sale_price');
            }
        );
    }
}
