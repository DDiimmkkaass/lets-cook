<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIngredientNutritionalValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ingredient_nutritional_values',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('ingredient_id')->unsigned()->index();
                $table->integer('nutritional_value_id')->unsigned()->index();

                $table->integer('value')->unsigned();

                $table->timestamps();

                $table->foreign('ingredient_id')->references('id')->on('ingredients')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('nutritional_value_id')->references('id')->on('nutritional_values')
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
        Schema::drop('ingredient_nutritional_values');
    }
}
