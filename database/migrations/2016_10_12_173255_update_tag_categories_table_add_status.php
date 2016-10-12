<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTagCategoriesTableAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'tag_categories',
            function (Blueprint $table) {
                $table->boolean('status')->default(true)->after('name');
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
            'tag_categories',
            function (Blueprint $table) {
                $table->dropColumn('status');
            }
        );
    }
}
