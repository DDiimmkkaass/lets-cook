<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'comments',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('commentable_id')->unsigned()->index();
                $table->string('commentable_type')->index();

                $table->integer('user_id')->nullable()->unsigned()->index();

                $table->string('name')->nullable();
                $table->text('comment')->nullable();

                $table->boolean('status')->default(false);

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate(
                    'cascade'
                );
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
        Schema::drop('comments');
    }
}
