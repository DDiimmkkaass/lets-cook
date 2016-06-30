<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasketRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'basket_recipe',
            function (Blueprint $table) {
                $table->integer('basket_id')->unsigned()->index();
                $table->integer('recipe_id')->unsigned()->index();

                $table->primary(['basket_id', 'recipe_id']);

                $table->foreign('basket_id')->references('id')->on('baskets')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('recipe_id')->references('id')->on('recipes')
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
        Schema::drop('basket_recipe');
    }
}
