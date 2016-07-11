<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateRecipesTableDropPrice extends Migration
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
                $table->dropColumn('price');
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
                $table->integer('price')->unsigned();
            }
        );
    }
}
