<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVariablesTableAddPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'variables',
            function (Blueprint $table) {
                $table->smallInteger('position')->unsigned()->after('status');
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
            'variables',
            function (Blueprint $table) {
                $table->dropColumn('position');
            }
        );
    }
}
