<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserInfoTableAddFirstOrderDateOldSiteOrdersCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'user_info',
            function (Blueprint $table) {
                $table->string('first_order_date')->nullable();
                $table->integer('old_site_orders_count')->unsigned();
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
            'user_info',
            function (Blueprint $table) {
                $table->dropColumn('first_order_date');
                $table->dropColumn('old_site_orders_count');
            }
        );
    }
}
