<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCommentsTableUpdateParentId extends Migration
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
                $table->dropForeign('comments_parent_id_foreign');

                $table->dropIndex('comments_parent_id_index');
            }
        );

        Schema::table(
            'comments',
            function (Blueprint $table) {
                $table->renameColumn('parent_id', 'tmp_parent_id');
            }
        );
        
        Schema::table(
            'comments',
            function (Blueprint $table) {
                $table->integer('parent_id')->nullable()->unsigned()->index()->after('id');

                $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade')->onUpdate(
                    'cascade'
                );
            }
        );

        DB::update('UPDATE comments SET `parent_id` = `tmp_parent_id`');

        Schema::table(
            'comments',
            function (Blueprint $table) {
                $table->dropColumn('tmp_parent_id');
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
        //
    }
}
