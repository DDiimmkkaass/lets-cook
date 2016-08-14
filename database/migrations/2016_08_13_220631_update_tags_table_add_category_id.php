<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTagsTableAddCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'tags',
            function (Blueprint $table) {
                $table->integer('category_id')->nullable()->unsigned()->index()->after('id');
                
                $table->foreign('category_id')->references('id')->on('tag_categories')
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
            'tags',
            function (Blueprint $table) {
                $table->dropForeign('tags_category_id_foreign');
                $table->dropIndex('tags_category_id_index');
                $table->dropColumn('category_id');
            }
        );
    }
}
