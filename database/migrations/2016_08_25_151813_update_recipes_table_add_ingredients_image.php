<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateRecipesTableAddIngredientsImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'recipes',
            function (Blueprint $table) {
                $table->string('ingredients_image')->nullable()->after('image');
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
            'recipes',
            function (Blueprint $table) {
                $table->dropColumn('ingredients_image');
            }
        );
    }
}
