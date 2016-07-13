<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateBasketRecipesTableDropPortions extends Migration
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
                $table->dropColumn('portions');
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
                $table->tinyInteger('portions')->unsigned()->after('main');
            }
        );
    }
}
