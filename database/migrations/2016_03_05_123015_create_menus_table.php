<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'menus',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('layout_position')->nullable();
                $table->string('template')->nullable();
                $table->string('class')->nullable();

                $table->boolean('show_title')->default(false);

                $table->integer('position')->unsigned();
                $table->boolean('status')->default(true);

                $table->timestamps();
            }
        );

        Schema::create(
            'menu_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('menu_id')->unsigned()->index();

                $table->string('locale')->index();

                $table->string('name')->nullable();

                $table->unique(['menu_id', 'locale']);

                $table->foreign('menu_id')->references('id')->on('menus')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('menu_translations');
        Schema::drop('menus');
    }
}
