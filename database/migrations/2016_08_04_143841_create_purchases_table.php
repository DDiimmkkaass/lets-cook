<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'purchases',
            function (Blueprint $table) {
                $table->increments('id');
                
                $table->integer('ingredient_id')->unsigned()->index();
                $table->integer('supplier_id')->unsigned()->index();
                
                $table->tinyInteger('week')->unsigneed();
                $table->string('year');
                
                $table->integer('count')->unsigned();
                $table->boolean('in_stock')->default(false);
                $table->integer('buy_count')->unsigned();
                $table->boolean('purchase_manager')->default(false);
                $table->integer('price')->unsigned();
                
                $table->foreign('ingredient_id')->references('id')->on('ingredients')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('supplier_id')->references('id')->on('suppliers')
                    ->onUpdate('cascade')->onDelete('cascade');
                
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
        Schema::drop('purchases');
    }
}
