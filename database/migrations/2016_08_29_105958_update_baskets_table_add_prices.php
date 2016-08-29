<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateBasketsTableAddPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'baskets',
            function (Blueprint $table) {
                $table->text('prices')->nullable()->after('price');
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
            'baskets',
            function (Blueprint $table) {
                $table->dropColumn('prices');
            }
        );
    }
}
