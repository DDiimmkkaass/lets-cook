<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCommentsTableAddImageDate extends Migration
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
                $table->string('image')->nullable()->after('name');
                $table->timestamp('date')->default(DB::raw('NOW()'))->after('status');
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
                $table->dropColumn('image');
                $table->dropColumn('date');
            }
        );
    }
}
