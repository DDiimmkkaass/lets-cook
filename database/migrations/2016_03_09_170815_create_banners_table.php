<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBannersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'banners',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('layout_position')->nullable();
                $table->string('class')->nullable();
                $table->boolean('show_title')->default(false);
                $table->boolean('status')->default(true);
                $table->integer('position')->unsigned();
                $table->string('template')->nullable();

                $table->timestamps();
            }
        );

        Schema::create(
            'banner_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('banner_id')->unsigned()->index();

                $table->string('locale')->index();
                $table->string('title')->nullable();

                $table->unique(['banner_id', 'locale']);

                $table->foreign('banner_id')->references('id')->on('banners')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('banner_translations');
        Schema::drop('banners');
    }
}
