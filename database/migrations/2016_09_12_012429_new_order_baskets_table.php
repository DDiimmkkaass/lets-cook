<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewOrderBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('basket_order');
    
        Schema::create(
            'order_baskets',
            function (Blueprint $table) {
                $table->increments('id');
    
                $table->integer('order_id')->unsigned()->index();
                $table->integer('weekly_menu_basket_id')->unsigned()->nullable()->index();
                $table->integer('basket_id')->unsigned()->nullable()->index();
                
                $table->integer('price')->unsigned();
                $table->string('name');
    
                $table->timestamps();
                
                $table->foreign('order_id')->references('id')->on('orders')
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('weekly_menu_basket_id')->references('id')->on('weekly_menu_baskets')
                    ->onDelete('set null')->onUpdate('cascade');
                $table->foreign('basket_id')->references('id')->on('baskets')
                    ->onDelete('set null')->onUpdate('cascade');
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
        Schema::drop('order_baskets');

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
}
