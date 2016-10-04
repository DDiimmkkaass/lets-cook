<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'booklets',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('year');
                $table->tinyInteger('week')->unsigneed();
                
                $table->string('link')->nullable();
                
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
        Schema::drop('booklets');
    }
}
