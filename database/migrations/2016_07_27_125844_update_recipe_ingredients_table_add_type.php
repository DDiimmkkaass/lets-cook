<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRecipeIngredientsTableAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'recipe_ingredients',
            function (Blueprint $table) {
                $table->tinyInteger('type')->unsigned()->after('ingredient_id');
            }
        );
    
        Schema::table(
            'recipes',
            function (Blueprint $table) {
                $table->dropColumn('home_ingredients');
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
            'recipe_ingredients',
            function (Blueprint $table) {
                $table->dropColumn('type');
            }
        );
    
        Schema::table(
            'recipes',
            function (Blueprint $table) {
                $table->text('home_ingredients')->nullable()->after('home_equipment');
            }
        );
    }
}
