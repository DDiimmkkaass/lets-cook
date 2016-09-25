<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_coupons',
            function (Blueprint $table) {
                $table->increments('id');
        
                $table->integer('user_id')->unsigned()->index();
                $table->integer('coupon_id')->unsigned()->index();
                
                $table->boolean('default')->default(false);
                
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('coupon_id')->references('id')->on('coupons')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('user_coupons');
    }
}
