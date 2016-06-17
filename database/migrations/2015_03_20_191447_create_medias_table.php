<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'medias',
            function (Blueprint $table) {
                $table->increments('id');

                $table->smallInteger('type');

                $table->integer('mediable_id')->nullable()->unsigned();
                $table->string('mediable_type', 255)->nullable();

                $table->string('src', 255)->nullable();
                $table->string('preview', 255)->nullable();

                $table->integer('position')->unsigned();

                $table->timestamps();
            }
        );

        Schema::create(
            'media_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('media_id')->unsigned();

                $table->string('locale')->index();

                $table->string('name', 255)->nullable();
                $table->text('description')->nullable();

                $table->unique(['media_id', 'locale']);
                $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('media_translations');
        Schema::drop('medias');
    }

}