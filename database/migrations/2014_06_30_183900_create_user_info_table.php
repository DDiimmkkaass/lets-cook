<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInfoTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_info',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('user_id')->unsigned()->index();

                $table->string('name', 255)->nullable();
                $table->string('phone', 255)->nullable();
                $table->enum('gender', ["male", "female"])->default("male");
                $table->date('birthday')->nullable()->default("0000-00-00");
                $table->string('avatar', 255)->nullable();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('user_info');
    }
}
