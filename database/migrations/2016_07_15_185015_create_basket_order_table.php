<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasketOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'basket_order',
            function (Blueprint $table) {
                $table->integer('basket_id')->unsigned()->index();
                $table->integer('order_id')->unsigned()->index();
                
                $table->primary(['basket_id', 'order_id']);
                
                $table->foreign('basket_id')->references('id')->on('baskets')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('basket_order');
    }
}
