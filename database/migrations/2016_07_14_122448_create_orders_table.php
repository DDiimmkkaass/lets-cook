<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'orders',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('user_id')->unsigned()->nullable()->index();
                
                $table->tinyInteger('type')->unsigned();
                $table->tinyInteger('subscribe_period')->unsigned();
                $table->tinyInteger('status')->unsigned();
                
                $table->string('full_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('additional_phone')->nullable();
                $table->boolean('verify_call')->default(false);
                
                $table->integer('total')->unsigned();
                
                $table->timestamp('delivery_date')->nullable();
                $table->string('delivery_time')->nullable();
                $table->integer('city_id')->unsigned()->nullable()->index();
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->text('comment')->nullable();
                
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::drop('orders');
    }
}
