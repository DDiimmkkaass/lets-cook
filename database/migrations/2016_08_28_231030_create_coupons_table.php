<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'coupons',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->string('code')->nullable()->unique();
                
                $table->string('name')->nullable();
                $table->string('description')->nullable();
                
                $table->tinyInteger('type')->unsigned();
    
                $table->integer('discount')->unsigned();
                $table->tinyInteger('discount_type')->unsigned();
    
                $table->integer('count')->unsigned();
                $table->integer('users_count')->unsigned();
                
                $table->timestamp('started_at')->nullable();
                $table->timestamp('expired_at')->nullable();
                
                $table->timestamps();
                
                $table->softDeletes();
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
        Schema::drop('coupons');
    }
}
