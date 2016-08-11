<?php

use App\Models\WeeklyMenu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateWeeklyMenusTableDropStartedAtEndedAtAddWeekYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'weekly_menus',
            function (Blueprint $table) {
                $table->smallInteger('year')->unsigned()->after('id');
                $table->tinyInteger('week')->unsigned()->after('id');
            }
        );
        
        foreach (WeeklyMenu::all() as $menu) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $menu->started_at);
            
            $menu->week = $date->weekOfYear;
            $menu->year = $date->year;
            
            $menu->save();
        }
        
        Schema::table(
            'weekly_menus',
            function (Blueprint $table) {
                $table->dropColumn('started_at');
                $table->dropColumn('ended_at');
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
            'weekly_menus',
            function (Blueprint $table) {
                $table->timestamp('ended_at')->nullable()->after('id');
                $table->timestamp('started_at')->nullable()->after('id');
            }
        );
        
        foreach (WeeklyMenu::all() as $menu) {
            $date = Carbon::create($menu->year, 1, 1, 0);
            
            $date->addWeek($menu->week);
            
            $menu->started_at = $date->startOfWeek();
            $menu->ended_at = $date->endOfWeek();
            
            $menu->save();
        }
        
        Schema::table(
            'weekly_menus',
            function (Blueprint $table) {
                $table->dropColumn('year');
                $table->dropColumn('week');
            }
        );
    }
}
