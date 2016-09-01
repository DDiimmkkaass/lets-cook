<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchasesTableAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'purchases',
            function (Blueprint $table) {
                $table->tinyInteger('type')->unsigned()->after('supplier_id');
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
            'purchases',
            function (Blueprint $table) {
                $table->dropColumn('type');
            }
        );
    }
}
