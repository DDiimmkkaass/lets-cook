<?php

use App\Models\Banner;
use Illuminate\Database\Migrations\Migration;

class PopulateBannersTable4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //how it works
        $position = 'how_it_works';
    
        if (!Banner::whereLayoutPosition($position)->first()) {
            $input = [
                'layout_position' => $position,
                'position'        => 0,
                'template'        => $position,
            ];
        
            foreach (config('app.locales') as $locale) {
                $input[$locale] = [
                    'title' => 'Как это работает',
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
