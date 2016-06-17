<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'news',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('slug', 255);
                $table->string('image', 255)->nullable();
                $table->string('external_url', 255)->nullable();
                $table->integer('position')->unsigned();
                $table->integer('view_count')->default(0);
                $table->boolean('status')->default(true);

                $table->timestamps();
            }
        );

        Schema::create(
            'news_translations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('news_id')->unsigned();
                $table->string('locale')->index();
                $table->string('name')->nullable();
                $table->text('short_content')->nullable();
                $table->text('content')->nullable();

                $table->string('meta_title')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();

                $table->unique(['news_id', 'locale']);
                $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('news_translations');
        Schema::drop('news');
    }
}