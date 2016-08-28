<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'order_comments',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('order_id')->unsigned()->index();
                $table->integer('user_id')->unsigned()->nullable()->index();
                
                $table->string('status')->nullable();
                $table->string('comment')->nullable();
                
                $table->timestamps();
                
                $table->foreign('order_id')->references('id')->on('orders')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('set null');
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
        Schema::drop('order_comments');
    }
}
