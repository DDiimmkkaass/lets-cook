<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWeeklyMenuBasketsTableAddDeliveryDate extends Migration
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
                $table->timestamp('delivery_date')->nullable()->after('prices');
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
                $table->dropColumn('delivery_date');
            }
        );
    }
}
