<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tags',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->string('slug')->nullable();

                $table->boolean('status')->default(true);
                $table->integer('position')->unsigned();
                
                $table->timestamps();
            }
        );

        Schema::create(
            'tag_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('tag_id')->unsigned();

                $table->string('locale')->index();

                $table->string('name')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();

                $table->unique(['tag_id', 'locale']);
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade')->onUpdate(
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
        Schema::drop('tag_translations');
        Schema::drop('tags');
    }
}
