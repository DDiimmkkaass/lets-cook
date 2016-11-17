<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBasketsTableAddMetaFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'baskets',
            function (Blueprint $table) {
                $table->text('meta_description')->nullable()->after('image');
                $table->string('meta_keywords')->nullable()->after('image');
                $table->string('meta_title')->nullable()->after('image');
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
            'baskets',
            function (Blueprint $table) {
                $table->dropColumn('meta_description');
                $table->dropColumn('meta_keywords');
                $table->dropColumn('meta_title');
            }
        );
    }
}
