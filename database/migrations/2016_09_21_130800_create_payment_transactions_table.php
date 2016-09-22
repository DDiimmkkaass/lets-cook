<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentTransactionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'payment_transactions',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order_id')->unsigned()->nullable()->index();

                $table->decimal('amount', 10, 2);
                $table->string('currency')->nullable();
                $table->string('status')->nullable();

                $table->text('description')->nullable();
                $table->text('data')->nullable();
                
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')
                    ->onDelete('set null')->onUpdate('cascade');
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
        Schema::drop('payment_transactions');
    }

}
