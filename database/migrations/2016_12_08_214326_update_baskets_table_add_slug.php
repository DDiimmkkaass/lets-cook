<?php

use App\Models\Basket;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBasketsTableAddSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'baskets',
            function (Blueprint $table) {
                $table->string('slug')->after('name');
            }
        );
    
        foreach (Basket::all() as $basket) {
            $basket->slug = str_slug($basket->getName());
            
            $basket->save();
            
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'baskets',
            function (Blueprint $table) {
                $table->dropColumn('slug');
            }
        );
    }
}
