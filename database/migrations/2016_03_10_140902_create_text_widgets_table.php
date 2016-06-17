<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTextWidgetsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'text_widgets',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('layout_position')->nullable();
                $table->boolean('status')->default(true);
                $table->integer('position')->nullable();

                $table->timestamps();
            }
        );

        Schema::create(
            'text_widget_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('text_widget_id')->unsigned()->index();

                $table->string('locale')->index();

                $table->string('title')->nullable();
                $table->text('content')->nullable();

                $table->unique(['text_widget_id', 'locale']);

                $table->foreign('text_widget_id')->references('id')->on('text_widgets')->onDelete('cascade');
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
        Schema::drop('text_widget_translations');
        Schema::drop('text_widgets');
    }

}