<?php

use App\Models\Banner;
use Illuminate\Database\Migrations\Migration;

class PopulateBannersTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //on recipes page
        $position = 'cook_with_us';
        
        if (!Banner::whereLayoutPosition($position)->first()) {
            $input = [
                'layout_position' => $position,
                'position'        => 0,
                'template'        => $position,
            ];
    
            foreach (config('app.locales') as $locale) {
                $input[$locale] = [
                    'title' => 'Готовьте с нами!',
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
