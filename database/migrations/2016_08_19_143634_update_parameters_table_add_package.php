<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateParametersTableAddPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'parameters',
            function (Blueprint $table) {
                $table->tinyInteger('package')->unsigned()->after('name');
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
            'parameters',
            function (Blueprint $table) {
                $table->dropColumn('package');
            }
        );
    }
}
