<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderIngredientsTableAddRecipeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'order_ingredients',
            function (Blueprint $table) {
                $table->integer('basket_recipe_id')->unsigned()->nullable()->index()->after('order_id');
                
                $table->foreign('basket_recipe_id')->references('id')->on('basket_recipes')
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
        Schema::table(
            'order_ingredients',
            function (Blueprint $table) {
                $table->dropForeign('order_ingredients_basket_recipe_id_foreign');
                $table->dropIndex('order_ingredients_basket_recipe_id_index');
                $table->dropColumn('basket_recipe_id');
            }
        );
    }
}
