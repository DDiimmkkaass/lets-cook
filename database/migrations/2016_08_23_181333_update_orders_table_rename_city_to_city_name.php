<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrdersTableRenameCityToCityName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->string('city_name')->nullable()->after('city');
            }
        );
        
        DB::update('UPDATE orders SET city_name = city');
        
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->dropColumn('city');
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
            'orders',
            function (Blueprint $table) {
                $table->string('city')->nullable()->after('city_name');
            }
        );
        
        DB::update('UPDATE orders SET city = city_name');
        
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->dropColumn('city_name');
            }
        );
    }
}
