<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrdersTableRemoveTypeSubscribePeriod extends Migration
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
                $table->dropColumn('type');
                $table->dropColumn('subscribe_period');
                
                $table->dropForeign('orders_parent_id_foreign');
                $table->dropIndex('orders_parent_id_index');
                $table->dropColumn('parent_id');
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
                $table->integer('parent_id')->unsigned()->nullable()->index()->after('id');
                
                $table->tinyInteger('subscribe_period')->unsigned()->after('user_id');
                $table->tinyInteger('type')->unsigned()->after('user_id');
    
                $table->foreign('parent_id')->references('id')->on('orders')
                    ->onUpdate('cascade')->onDelete('set null');
            }
        );
    }
}
