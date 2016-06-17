<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNewsTableAddPublishAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'news',
            function (Blueprint $table) {
                $table->timestamp('publish_at')->default(DB::raw('NOW()'))->after('status');
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
            'news',
            function (Blueprint $table) {
                $table->dropColumn('publish_at');
            }
        );
    }
}
