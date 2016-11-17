<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_socials',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('user_id')->unsigned()->index();
                
                $table->enum('provider', ['Vkontakte', 'Facebook'])->index();
                
                $table->string('external_id', 32)->index();
                $table->string('token', 255)->nullable();
    
                $table->string('profile_url')->nullable();
                
                $table->timestamps();
                
                $table->index(['provider', 'external_id']);
                
                $table->foreign('user_id')->references('id')->on('users')
                    ->onDelete('cascade')->onupdate('cascade');
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
        Schema::drop('user_socials');
    }
}
