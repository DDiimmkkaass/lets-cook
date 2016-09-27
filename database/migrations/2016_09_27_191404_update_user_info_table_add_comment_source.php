<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserInfoTableAddCommentSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'user_info',
            function (Blueprint $table) {
                $table->string('comment')->nullable();
                $table->string('source')->nullable();
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
            'user_info',
            function (Blueprint $table) {
                $table->dropColumn('comment');
                $table->dropColumn('source');
            }
        );
    }
}
