<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserInfoAddAdditionalPhoneCityIdCityName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'user_info',
            function (Blueprint $table) {
                $table->string('address')->nullable()->after('phone');
                $table->string('city_name')->nullable()->after('phone');
                $table->integer('city_id')->unsigned()->nullable()->index()->after('phone');
    
                $table->string('additional_phone')->nullable()->after('phone');
                
                $table->foreign('city_id')->references('id')->on('cities')
                    ->onUpdate('cascade')->onDelete('set null');
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
        Schema::table(
            'user_info',
            function (Blueprint $table) {
                $table->dropColumn('additional_phone');
                $table->dropColumn('city_name');
                $table->dropColumn('address');
                
                $table->dropForeign('user_info_city_id_foreign');
                $table->dropIndex('user_info_city_id_index');
                $table->dropColumn('city_id');
            }
        );
    }
}
