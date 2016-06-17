<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'questions',
            function (Blueprint $table) {
                $table->increments('id');

                $table->smallInteger('position')->unsigned();
                $table->boolean('status')->default(true);

                $table->timestamps();
            }
        );

        Schema::create(
            'question_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('question_id')->unsigned();

                $table->string('locale')->index();

                $table->text('question')->nullable();
                $table->text('answer')->nullable();

                $table->unique(['question_id', 'locale']);
                $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade')->onUpdate(
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
        Schema::drop('question_translations');
        Schema::drop('questions');
    }
}
