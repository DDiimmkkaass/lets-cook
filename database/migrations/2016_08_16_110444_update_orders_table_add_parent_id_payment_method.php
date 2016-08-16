<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersTableAddParentIdPaymentMethod extends Migration
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
                $table->integer('parent_id')->unsigned()->nullable()->index()->after('id');
                
                $table->tinyInteger('payment_method')->unsigned()->after('subscribe_period');
                
                $table->foreign('parent_id')->references('id')->on('orders')
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
                $table->dropColumn('payment_method');
                
                $table->dropForeign('orders_parent_id_foreign');
                $table->dropIndex('orders_parent_id_index');
                $table->dropColumn('parent_id');
            }
        );
    }
}
