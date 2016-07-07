<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasketRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'basket_recipes',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('basket_id')->unsigned()->index();
                $table->integer('recipe_id')->unsigned()->index();

                $table->boolean('main')->default(false);
                $table->tinyInteger('portions')->unsigned();
                $table->tinyInteger('position')->unsigned();

                $table->timestamps();

                $table->foreign('basket_id')->references('id')->on('baskets')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('recipe_id')->references('id')->on('recipes')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('basket_recipes');
    }
}
