<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'menu_items',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('menu_id')->nullable()->unsigned()->index();

                $table->string('link')->nullable();
                $table->string('class')->nullable();

                $table->integer('position')->nullable()->unsigned();

                $table->boolean('status')->default(true);
                $table->boolean('register_only')->default(false);

                $table->timestamps();

                $table->foreign('menu_id')->references('id')->on('menus')->onUpdate('cascade')->onDelete('cascade');
            }
        );

        Schema::create(
            'menu_item_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('menu_item_id')->unsigned()->index();

                $table->string('locale')->index();

                $table->string('name')->nullable();
                $table->string('title')->nullable();
                $table->string('description')->nullable();

                $table->unique(['menu_item_id', 'locale']);

                $table->foreign('menu_item_id')->references('id')->on('menu_items')->onUpdate('cascade')->onDelete(
                    'cascade'
                );
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
        Schema::drop('menu_item_translations');
        Schema::drop('menu_items');
    }
}
