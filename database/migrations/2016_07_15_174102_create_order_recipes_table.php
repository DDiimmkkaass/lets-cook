<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'order_recipes',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('order_id')->unsigned()->index();
                $table->integer('basket_recipe_id')->unsigned()->nullable()->index();
                $table->string('name')->nullable();
                
                $table->timestamps();
                
                $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('basket_recipe_id')->references('id')->on('basket_recipes')
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
        Schema::drop('order_recipes');
    }
}
