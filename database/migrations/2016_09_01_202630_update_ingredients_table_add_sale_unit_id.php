<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateIngredientsTableAddSaleUnitId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'ingredients',
            function (Blueprint $table) {
                $table->integer('sale_unit_id')->unsigned()->nullable()->index()->after('unit_id');
                
                $table->foreign('sale_unit_id')->references('id')->on('units')
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
            'ingredients',
            function (Blueprint $table) {
                $table->dropForeign('ingredients_sale_unit_id_foreign');
                $table->dropIndex('ingredients_sale_unit_id_index');
                $table->dropColumn('sale_unit_id');
            }
        );
    }
}
