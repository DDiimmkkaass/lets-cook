<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'shares',
            function (Blueprint $table) {
                $table->increments('id');
        
                $table->string('image')->nullable();
                $table->string('link')->nullable();
    
                $table->tinyInteger('position')->unsigned();
                $table->boolean('status')->default(true);
                
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
        Schema::drop('shares');
    }
}
