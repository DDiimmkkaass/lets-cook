<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'cards',
            function (Blueprint $table) {
                $table->increments('id');
    
                $table->bigInteger('invoice_id')->unsigned()->nullable()->index();
                
                $table->integer('user_id')->unsigned()->index();
    
                $table->string('name')->nullable();
                $table->string('number')->nullable();
                
                $table->boolean('default')->default(false);
                
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                
                $table->timestamps();
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
        Schema::drop('cards');
    }
}
