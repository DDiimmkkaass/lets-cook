<?php

use App\Models\Basket;
use App\Models\BasketRecipe;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;

/**
 * Class _WeeklyMenusSeeder
 */
class _WeeklyMenusSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeeklyMenu::whereNotNull('id')->forceDelete();
        DB::statement('ALTER TABLE `'.((new WeeklyMenu())->getTable()).'` AUTO_INCREMENT=1');
        
        foreach (range(0, 5) as $index) {
            $week = Carbon::now()->weekOfYear + $index;
            
            $input = [
                'week' => $week,
                'year' => Carbon::create(Carbon::now()->year, 1, 1)->startOfWeek()->addWeeks($week)->year,
            ];
            
            $weekly_menu = new WeeklyMenu($input);
            $weekly_menu->save();
            
            foreach (Basket::basic()->get() as $basket) {
                $portions = rand(0, 1) ? 2 : 4;
                
                $count = $basket->allowed_recipes()->where('portions', $portions)->count();
                
                if ($count) {
                    $_input = [
                        'weekly_menu_id' => $weekly_menu->id,
                        'basket_id'      => $basket->id,
                        'portions'       => $portions,
                    ];
                    
                    $_basket = WeeklyMenuBasket::create($_input);
                    
                    $recipes = $basket->allowed_recipes()
                        ->where('portions', $portions)
                        ->get()
                        ->random(rand($count > 3 ? 3 : $count, $count < 5 ? $count : 5));
                    
                    foreach ($recipes as $key => $recipe) {
                        BasketRecipe::create(
                            [
                                'weekly_menu_basket_id' => $_basket->id,
                                'recipe_id'             => $recipe->id,
                                'main'                  => rand(0, 1),
                                'position'              => $key,
                            ]
                        );
                    }
                }
            }
        }
    }
}
