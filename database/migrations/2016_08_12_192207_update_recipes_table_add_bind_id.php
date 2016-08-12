<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateRecipesTableAddBindId extends Migration
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
                $table->string('bind_id', 32)->nullable()->after('id');
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
                $table->dropColumn('bind_id');
            }
        );
    }
}
