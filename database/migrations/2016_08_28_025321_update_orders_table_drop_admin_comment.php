<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrdersTableDropAdminComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->dropColumn('admin_comment');
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
            'orders',
            function (Blueprint $table) {
                $table->text('admin_comment')->after('comment');
            }
        );
    }
}
