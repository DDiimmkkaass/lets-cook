<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('image')->nullable();

            $table->text('recipe')->nullable();
            $table->text('helpful_hints')->nullable();
            $table->integer('portions')->unsigned();
            $table->integer('cooking_time')->unsigned();

            $table->text('home_equipment')->nullable();
            $table->text('home_ingredients')->nullable();

            $table->boolean('status')->default(true);

            $table->integer('price')->unsigned();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipes');
    }
}
