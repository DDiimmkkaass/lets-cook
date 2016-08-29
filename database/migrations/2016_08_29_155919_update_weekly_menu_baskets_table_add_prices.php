<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWeeklyMenuBasketsTableAddPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'weekly_menu_baskets',
            function (Blueprint $table) {
                $table->text('prices')->nullable()->after('portions');
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
            'weekly_menu_baskets',
            function (Blueprint $table) {
                $table->dropColumn('prices');
            }
        );
    }
}
