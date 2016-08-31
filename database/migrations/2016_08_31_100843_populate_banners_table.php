<?php

use App\Models\Banner;
use Illuminate\Database\Migrations\Migration;

class PopulateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //what we offer
        $position = 'what_we_offer';
        
        if (!Banner::whereLayoutPosition($position)->first()) {
            $input = [
                'layout_position' => $position,
                'position'        => 0,
                'template'        => $position,
            ];
            
            foreach (config('app.locales') as $locale) {
                $input[$locale] = [
                    'title' => 'Что мы предлагаем',
                ];
            }
            
            Banner::create($input);
        }
        
        //what makes us different
        $position = 'what_makes_us_different';
        
        if (!Banner::whereLayoutPosition($position)->first()) {
            $input = [
                'layout_position' => $position,
                'position'        => 0,
                'template'        => $position,
                'show_title'      => true,
            ];
            
            foreach (config('app.locales') as $locale) {
                $input[$locale] = [
                    'title' => 'Чем мы отличаемся от других',
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
