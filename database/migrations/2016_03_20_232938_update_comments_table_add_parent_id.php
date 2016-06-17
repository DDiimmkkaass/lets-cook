<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCommentsTableAddParentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'comments',
            function (Blueprint $table) {
                $table->integer('parent_id')->nullable()->unsigned()->index();

                $table->foreign('parent_id')->references('id')->on('comments')->onDelete('set null')->onUpdate(
                    'cascade'
                );
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
            'comments',
            function (Blueprint $table) {
                $table->dropForeign('comments_parent_id_foreign');
                            
                $table->dropIndex('comments_parent_id_index');
                
                $table->dropColumn('parent_id');
            }
        );
    }
}
