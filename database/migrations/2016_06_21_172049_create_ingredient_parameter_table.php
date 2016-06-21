<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIngredientParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ingredient_parameter',
            function (Blueprint $table) {
                $table->integer('ingredient_id')->unsigned()->index();
                $table->integer('parameter_id')->unsigned()->index();

                $table->primary(['parameter_id', 'ingredient_id']);

                $table->foreign('ingredient_id')->references('id')->on('ingredients')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('parameter_id')->references('id')->on('parameters')
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
        Schema::drop('ingredient_parameter');
    }
}
