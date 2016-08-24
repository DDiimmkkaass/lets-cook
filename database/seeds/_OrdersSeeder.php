<?php

use App\Models\Basket;
use App\Models\BasketRecipe;
use App\Models\City;
use App\Models\Group;
use App\Models\Order;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\RecipeIngredient;
use App\Models\WeeklyMenu;

/**
 * Class _OrderSeeder
 */
class _OrdersSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Order())->getTable()).'` AUTO_INCREMENT=1');
        
        $delivery_times = config('order.delivery_times');
        
        for ($i = 1; $i < 10; $i++) {
            $type = rand(1, 2);
            
            $city_id = rand(0, 1) > 0 ? City::all()->random(1)->id : null;
            
            if ($i % 2 == 0) {
                $delivery_date = Carbon::now()->endOfWeek()->startOfDay()->format('d-m-Y');
            } else {
                $delivery_date = Carbon::now()->endOfWeek()->addDay()->startOfDay()->format('d-m-Y');
            }
            
            $input = [
                'user_id'          => Group::clients()->first()->users()->get()->random(1)->id,
                'type'             => $type,
                'subscribe_period' => $type == 2 ? rand(1, 2) : 0,
                'full_name'        => $this->faker->name,
                'email'            => $this->faker->email,
                'phone'            => $this->faker->phoneNumber,
                'additional_phone' => $this->faker->phoneNumber,
                'verify_call'      => rand(0, 1),
                'total'            => rand(1000, 10000),
                'delivery_date'    => $delivery_date,
                'delivery_time'    => $delivery_times[rand(0, count($delivery_times) - 1)],
                'city_id'          => $city_id,
                'city_name'        => $city_id ? null : $this->faker->city,
                'address'          => $this->faker->address,
                'comment'          => $this->getLocalizedFaker()->realText(rand(50, 100)),
            ];
            
            $order = new Order($input);
            $order->save();
            
            $weekly_menu = WeeklyMenu::current()->first();
            
            if ($weekly_menu) {
                $basket = $weekly_menu->baskets()->with('recipes')->get()->random(1);
                
                if ($basket) {
                    foreach ($basket->recipes as $recipe) {
                        OrderRecipe::create(
                            [
                                'order_id'         => $order->id,
                                'basket_recipe_id' => $recipe->id,
                                'name'             => $recipe->recipe->name,
                            ]
                        );
                    }
                }
                
                $recipes = $basket->recipes->pluck('recipe_id')->toArray();
                $ingredients = RecipeIngredient::with('ingredient')
                    ->home()
                    ->whereIn('recipe_id', $recipes)
                    ->get();
                
                $ingredients_count = $ingredients->count();
                if ($ingredients_count) {
                    $ingredients = $ingredients->random(rand(1, $ingredients_count > 2 ? 2 : $ingredients_count));
                    
                    if (count($ingredients) == 1) {
                        $basket_recipe_id = BasketRecipe::where('weekly_menu_basket_id', $basket->id)
                            ->where('recipe_id', $ingredients->recipe_id)
                            ->first()
                            ->id;
                        
                        OrderIngredient::create(
                            [
                                'order_id'         => $order->id,
                                'basket_recipe_id' => $basket_recipe_id,
                                'ingredient_id'    => $ingredients->ingredient_id,
                                'name'             => $ingredients->ingredient->name,
                                'count'            => rand(1, 10),
                            ]
                        );
                    } else {
                        foreach ($ingredients as $ingredient) {
                            $basket_recipe_id = BasketRecipe::where('weekly_menu_basket_id', $basket->id)
                                ->where('recipe_id', $ingredient->recipe_id)
                                ->first()
                                ->id;
                            
                            OrderIngredient::create(
                                [
                                    'order_id'         => $order->id,
                                    'basket_recipe_id' => $basket_recipe_id,
                                    'ingredient_id'    => $ingredient->ingredient_id,
                                    'name'             => $ingredient->ingredient->name,
                                    'count'            => rand(1, 10),
                                ]
                            );
                        }
                    }
                }
            }
            
            $baskets_count = Basket::additional()->count();
            if ($baskets_count) {
                $baskets = Basket::additional()->get()
                    ->random(rand(1, ($baskets_count < 2 ? $baskets_count : 2)))
                    ->pluck('id')->toArray();
                
                if (count($baskets)) {
                    $order->baskets()->sync($baskets);
                }
            }
        }
    }
}
