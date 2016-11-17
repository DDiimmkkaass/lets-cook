<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRecipesTableAddMetaFields extends Migration
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
                $table->text('meta_description')->nullable()->after('ingredients_image');
                $table->string('meta_keywords')->nullable()->after('ingredients_image');
                $table->string('meta_title')->nullable()->after('ingredients_image');
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
                $table->dropColumn('meta_description');
                $table->dropColumn('meta_keywords');
                $table->dropColumn('meta_title');
            }
        );
    }
}
