<?php

use App\Models\Banner;
use Illuminate\Database\Migrations\Migration;

class PopulateBannersTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //what you get
        $position = 'what_you_get';
        
        if (!Banner::whereLayoutPosition($position)->first()) {
            $input = [
                'layout_position' => $position,
                'position'        => 0,
                'template'        => $position,
            ];
            
            foreach (config('app.locales') as $locale) {
                $input[$locale] = [
                    'title' => 'Вы получите',
                ];
            }
            
            Banner::create($input);
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
