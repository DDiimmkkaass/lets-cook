<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserInfoTableRemoveNameAddFullName extends Migration
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
                $table->text('full_name');
            }
        );

        DB::update('update `user_info` set `full_name` = `name` where 1');

        Schema::table(
            'user_info',
            function (Blueprint $table) {
                $table->dropColumn('name');
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
                $table->text('name');
            }
        );

        DB::update('update `user_info` set `name` = `full_name` where 1');

        Schema::table(
            'user_info',
            function (Blueprint $table) {
                $table->dropColumn('full_name');
            }
        );
    }
}
