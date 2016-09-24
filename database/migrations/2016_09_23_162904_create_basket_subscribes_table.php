<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasketSubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'basket_subscribes',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('user_id')->unsigned()->index();
                $table->integer('basket_id')->unsigned()->index();
    
                $table->tinyInteger('subscribe_period')->unsigned();
                
                $table->tinyInteger('delivery_date')->unsigned();
                $table->string('delivery_time')->nullable();
    
                $table->tinyInteger('payment_method')->unsigned();
                
                $table->tinyInteger('portions')->unsigned();
                $table->tinyInteger('recipes')->unsigned();
                
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('basket_id')->references('id')->on('baskets')
                    ->onUpdate('cascade')->onDelete('cascade');
            }
        );
    
        Schema::create(
            'basket_subscribe',
            function (Blueprint $table) {
                $table->integer('subscribe_id')->unsigned()->index();
                $table->integer('basket_id')->unsigned()->index();
    
                $table->primary(['subscribe_id', 'basket_id']);
    
                $table->foreign('subscribe_id')->references('id')->on('basket_subscribes')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('basket_id')->references('id')->on('baskets')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('basket_subscribe');
        Schema::drop('basket_subscribes');
    }
}
