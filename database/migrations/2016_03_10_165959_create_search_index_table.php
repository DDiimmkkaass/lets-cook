<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSearchIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'search_indexes',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('searchable_id')->unsigned();
                $table->string('searchable_type', 255);

                $table->string('image')->nullable();

                $table->timestamps();
            }
        );

        Schema::create(
            'search_index_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('search_index_id')->unsigned();

                $table->string('locale')->index();

                $table->string('title', 255)->nullable();
                $table->text('description')->nullable();
                $table->text('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();

                $table->unique(['search_index_id', 'locale']);
                $table->foreign('search_index_id')->references('id')->on('search_indexes')->onUpdate(
                    'cascade'
                )->onDelete('cascade');
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
        Schema::drop('search_index_translations');
        Schema::drop('search_indexes');
    }
}
