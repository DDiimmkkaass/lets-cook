<?php

use App\Models\City;
use App\Models\UserInfo;
use Illuminate\Database\Migrations\Migration;

class ImportCitiesFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cities = City::all()->keyBy('name')->toArray();
        
        foreach (UserInfo::all() as $user) {
            if (!$user->city_id && !empty($user->city_name)) {
                if (!isset($cities[$user->city_name])) {
                    $city = City::create(['name' => $user->city_name]);
                    
                    $cities[$user->city_name] = $city->toArray();
                    
                    $city_id = $city->id;
                } else {
                    $city_id = $cities[$user->city_name]['id'];
                }
                
                $user->city_id = $city_id;
                
                $user->save();
            }
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
