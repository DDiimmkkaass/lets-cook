<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePagesTableAddTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'pages',
            function (Blueprint $table) {
                $table->string('template')->nullable()->after('image');
            }
        );
        
        Page::whereSlug('home')->first()->update(['template' => 'home']);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'pages',
            function (Blueprint $table) {
                $table->dropColumn('template');
            }
        );
    }
}
