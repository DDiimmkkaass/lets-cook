<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCouponsTableAddUserId extends Migration
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
                $table->integer('user_id')->nullable()->unsigned()->index()->after('id');
                
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('set null');
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
                $table->dropForeign('coupons_user_id_foreign');
                $table->dropIndex('coupons_user_id_index');
                $table->dropColumn('user_id');
            }
        );
    }
}
