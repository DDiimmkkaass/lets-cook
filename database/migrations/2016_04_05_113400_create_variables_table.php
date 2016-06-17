<?php

use App\Models\Variable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Settings;

class CreateVariablesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'variables',
            function (Blueprint $table) {
                $table->increments('id');

                $table->tinyInteger('type')->default(Variable::$defaultType);

                $table->string('key')->nullable();

                $table->string('name')->nullable();
                $table->string('description')->nullable();
                $table->boolean('multilingual')->default(false);

                $table->string('value')->nullable();
                $table->boolean('status')->default(true);

                $table->timestamps();
            }
        );

        Schema::create(
            'variable_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('variable_id')->unsigned()->index();

                $table->string('locale')->index();
                $table->string('text')->nullable();

                $table->unique(['variable_id', 'locale']);

                $table->foreign('variable_id')->references('id')->on('variables')->onDelete('cascade')->onUpdate(
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
        Schema::drop('variable_translations');
        Schema::drop('variables');
    }

}
