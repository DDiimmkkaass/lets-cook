<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBasketRecipesTableDropMain extends Migration
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
                $table->dropColumn('main');
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
                $table->boolean('main')->default(false)->after('recipe_id');
            }
        );
    }
}
