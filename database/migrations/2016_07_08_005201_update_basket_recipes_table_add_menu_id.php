<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateBasketRecipesTableAddMenuId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'basket_recipes',
            function (Blueprint $table) {
                $table->integer('weekly_menu_id')->unsigned()->nullable()->index()->after('id');

                $table->foreign('weekly_menu_id')->references('id')->on('weekly_menus')
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
        Schema::table(
            'basket_recipes',
            function (Blueprint $table) {
                $table->dropForeign('basket_recipes_weekly_menu_id_foreign');

                $table->dropIndex('basket_recipes_weekly_menu_id_index');

                $table->dropColumn('weekly_menu_id');
            }
        );
    }
}
