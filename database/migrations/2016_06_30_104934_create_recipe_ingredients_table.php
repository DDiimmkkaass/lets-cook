<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecipeIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'recipe_ingredients',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('recipe_id')->unsigned()->index();
                $table->integer('ingredient_id')->unsigned()->index();

                $table->integer('count')->unsigned();
                $table->integer('position')->unsigned();
                $table->boolean('main')->default(false);

                $table->timestamps();

                $table->foreign('recipe_id')->references('id')->on('recipes')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('ingredient_id')->references('id')->on('ingredients')
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
        Schema::drop('recipe_ingredients');
    }
}
