<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecipeStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'recipe_steps',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('recipe_id')->unsigned()->index();

                $table->string('name')->nullable();
                $table->text('description')->nullable();

                $table->string('image')->nullable();

                $table->integer('position')->unsigned();

                $table->timestamps();

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
        Schema::drop('recipe_steps');
    }
}
