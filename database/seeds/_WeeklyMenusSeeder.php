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
        
        $now = Carbon::now();
        $now1 = Carbon::now();
        
        foreach (range(1, 5) as $index) {
            $input = [
                'started_at' => $now->startOfWeek(),
                'ended_at'   => $now1->endOfWeek(),
            ];
            
            $weekly_menu = new WeeklyMenu($input);
            $weekly_menu->save();
            
            $baskets_count = Basket::basic()->count();
            $baskets = Basket::basic()->get()->random(rand(2, $baskets_count));
    
            foreach ($baskets as $basket) {
                $_input = [
                    'weekly_menu_id' => $weekly_menu->id,
                    'basket_id'      => $basket->id,
                    'portions'       => rand(0, 1) ? 2 : 4,
                ];
                
                $_basket = WeeklyMenuBasket::create($_input);
                
                $count = $basket->allowed_recipes()->count();
                foreach ($basket->allowed_recipes()->get()->random(rand(3, $count < 5 ? $count : 5)) as $recipe) {
                    BasketRecipe::create(
                        [
                            'weekly_menu_basket_id' => $_basket->id,
                            'recipe_id'             => $recipe->id,
                            'main'                  => rand(0, 1),
                        ]
                    );
                }
            }
            
            $now = Carbon::now()->subDays(7 * $index);
            $now1 = Carbon::now()->subDays(7 * $index);
        }
    }
}
