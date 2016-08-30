<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'articles',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->string('slug', 255);
                $table->string('image', 255)->nullable();
                $table->string('external_url', 255)->nullable();
                $table->integer('position')->unsigned();
                $table->integer('view_count')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamp('publish_at')->default(DB::raw('NOW()'));
                
                $table->timestamps();
            }
        );
        
        Schema::create(
            'article_translations',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('article_id')->unsigned();
                
                $table->string('locale')->index();
                $table->string('name')->nullable();
                $table->text('short_content')->nullable();
                $table->text('content')->nullable();
                
                $table->string('meta_title')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();
                
                $table->unique(['article_id', 'locale']);
                $table->foreign('article_id')->references('id')->on('articles')
                    ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('article_translations');
        Schema::drop('articles');
    }
}