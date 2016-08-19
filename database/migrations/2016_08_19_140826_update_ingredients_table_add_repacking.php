<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIngredientsTableAddRepacking extends Migration
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
                $table->boolean('repacking')->default(false)->after('sale_price');
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
                $table->dropColumn('repacking');
            }
        );
    }
}
