<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCouponsTableAddUsersType extends Migration
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
                $table->tinyInteger('users_type')->unsignd()->after('users_count');
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
            'coupons',
            function (Blueprint $table) {
                $table->dropColumn('users_type');
            }
        );
    }
}
