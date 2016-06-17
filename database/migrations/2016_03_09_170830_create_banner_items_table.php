<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBannerItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'banner_items',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('banner_id')->nullable()->unsigned()->index();

                $table->string('image')->nullable();
                $table->string('link')->nullable();

                $table->integer('position')->nullable()->unsigned();
                $table->boolean('status')->default(true);

                $table->timestamps();

                $table->foreign('banner_id')->references('id')->on('banners')->onUpdate('cascade')->onDelete('cascade');
            }
        );

        Schema::create(
            'banner_item_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('banner_item_id')->unsigned()->index();

                $table->string('locale')->index();

                $table->string('title')->nullable();
                $table->string('text')->nullable();

                $table->unique(['banner_item_id', 'locale']);

                $table->foreign('banner_item_id')->references('id')->on('banner_items')->onUpdate('cascade')->onDelete(
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
        Schema::drop('banner_item_translations');
        Schema::drop('banner_items');
    }
}
