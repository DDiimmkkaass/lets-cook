<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWeeklyMenuBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'weekly_menu_baskets',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('weekly_menu_id')->unsigned()->index();
                $table->integer('basket_id')->unsigned()->index();
                
                $table->tinyInteger('portions')->unsigned();
                
                $table->timestamps();
                
                $table->foreign('weekly_menu_id')->references('id')->on('weekly_menus')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('basket_id')->references('id')->on('baskets')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('weekly_menu_baskets');
    }
}
