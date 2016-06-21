<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ingredients',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('supplier_id')->unsigned()->nullable()->index();
                $table->integer('category_id')->unsigned()->nullable()->index();
                $table->integer('unit_id')->unsigned()->nullable()->index();

                $table->string('name')->nullable();
                $table->string('title')->nullable();
                $table->string('image')->nullable();
                $table->integer('price')->unsigned();

                $table->softDeletes();

                $table->timestamps();

                $table->foreign('supplier_id')->references('id')->on('suppliers')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('category_id')->references('id')->on('categories')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('unit_id')->references('id')->on('units')
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
        Schema::drop('ingredients');
    }
}
