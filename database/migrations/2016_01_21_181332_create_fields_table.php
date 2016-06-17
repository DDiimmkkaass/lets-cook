<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'fields',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('fieldable_id')->nullable()->unsigned();
                $table->string('fieldable_type', 255)->nullable();

                $table->smallInteger('type');
                $table->text('value')->nullable();

                $table->timestamps();
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
        Schema::drop('fields');
    }

}