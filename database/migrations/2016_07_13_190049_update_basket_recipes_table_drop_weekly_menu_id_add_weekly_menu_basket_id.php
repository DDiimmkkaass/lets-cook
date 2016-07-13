<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateBasketRecipesTableDropWeeklyMenuIdAddWeeklyMenuBasketId extends Migration
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
                $table->integer('weekly_menu_basket_id')->unsigned()->nullable()->after('basket_id')->index();
    
                $table->foreign('weekly_menu_basket_id')->references('id')->on('weekly_menu_baskets')
                    ->onUpdate('cascade')->onDelete('cascade');
                
                $table->dropForeign('basket_recipes_weekly_menu_id_foreign');
                $table->dropIndex('basket_recipes_weekly_menu_id_index');
                $table->dropColumn('weekly_menu_id');
            }
        );
        
        DB::statement('ALTER TABLE `basket_recipes` CHANGE `basket_id` `basket_id` INT UNSIGNED NULL DEFAULT NULL');
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
                $table->dropForeign('basket_recipes_weekly_menu_basket_id_foreign');
                $table->dropIndex('basket_recipes_weekly_menu_basket_id_index');
                $table->dropColumn('weekly_menu_basket_id');

                $table->integer('weekly_menu_id')->unsigned()->nullable()->index()->after('id');

                $table->foreign('weekly_menu_id')->references('id')->on('weekly_menus')
                    ->onUpdate('cascade')->onDelete('cascade');
            }
        );
        
        DB::statement('ALTER TABLE `basket_recipes` CHANGE `basket_id` `basket_id` INT UNSIGNED NOT NULL');
    }
}
