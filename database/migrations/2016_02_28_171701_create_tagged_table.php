<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaggedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tagged',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('taggable_id')->unsigned()->index();
                $table->string('taggable_type', 255)->index();

                $table->integer('tag_id')->unsigned()->index();

                $table->timestamps();

                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('tagged');
    }
}
