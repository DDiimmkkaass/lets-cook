<?php

use App\Models\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateCouponsTableRemoveDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Coupon::whereNotNull('deleted_at')->delete();
        
        Schema::table(
            'coupons',
            function (Blueprint $table) {
                $table->dropColumn('deleted_at');
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
            'coupons',
            function (Blueprint $table) {
                $table->softDeletes();
            }
        );
    }
}
