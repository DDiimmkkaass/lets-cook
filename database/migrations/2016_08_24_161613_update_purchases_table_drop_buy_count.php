<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePurchasesTableDropBuyCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'purchases',
            function (Blueprint $table) {
                $table->dropColumn('buy_count');
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
            'purchases',
            function (Blueprint $table) {
                $table->integer('buy_count')->unsigned()->after('in_stock');
            }
        );
    }
}
