<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrdersTableAddCouponId extends Migration
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
                $table->integer('coupon_id')->unsigned()->nullable()->index()->after('user_id');
                
                $table->foreign('coupon_id')->references('id')->on('coupons')
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
            'orders',
            function (Blueprint $table) {
                $table->dropForeign('orders_coupon_id_foreign');
                $table->dropIndex('orders_coupon_id_index');
                $table->dropColumn('coupon_id');
            }
        );
    }
}
