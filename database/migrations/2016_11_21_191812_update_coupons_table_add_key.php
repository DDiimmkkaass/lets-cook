<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCouponsTableAddKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'coupons',
            function (Blueprint $table) {
                $table->string('key', 50)->nullable()->after('description');
            }
        );
        
        DB::update('update `coupons` set `key` = \'register\' where `name` = \'Купон на скидку при регистрации\'');
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'coupons',
            function (Blueprint $table) {
                $table->dropColumn('key');
            }
        );
    }
}
