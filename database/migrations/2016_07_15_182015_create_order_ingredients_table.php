<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'order_ingredients',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('order_id')->unsigned()->index();
                $table->integer('ingredient_id')->unsigned()->nullable()->index();
                $table->string('name')->nullable();
                $table->integer('count')->unsigned();
                
                $table->timestamps();
                
                $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('ingredient_id')->references('id')->on('ingredients')
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
        Schema::drop('order_ingredients');
    }
}
