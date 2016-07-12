<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateRecipeIngredientsTableMakeCountFloat extends Migration
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
                $table->float('count_new')->unsigned()->after('count');
            }
        );

        DB::update('UPDATE `recipe_ingredients` SET `count_new` = `count`');

        Schema::table(
            'recipe_ingredients',
            function (Blueprint $table) {
                $table->dropColumn('count');
            }
        );

        Schema::table(
            'recipe_ingredients',
            function (Blueprint $table) {
                $table->renameColumn('count_new', 'count');
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
                $table->integer('count_new')->unsigned()->after('count');
            }
        );

        DB::update('UPDATE `recipe_ingredients` SET `count_new` = `count`');

        Schema::table(
            'recipe_ingredients',
            function (Blueprint $table) {
                $table->dropColumn('count');
            }
        );

        Schema::table(
            'recipe_ingredients',
            function (Blueprint $table) {
                $table->renameColumn('count_new', 'count');
            }
        );
    }
}
