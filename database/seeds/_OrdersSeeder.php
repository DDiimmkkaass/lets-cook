<?php

use App\Models\Basket;
use App\Models\City;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\User;
use App\Models\WeeklyMenuBasket;

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
            
            $input = [
                'user_id'          => User::all()->random(1)->id,
                'type'             => $type,
                'subscribe_period' => $type == 2 ? rand(1, 2) : 0,
                'full_name'        => $this->faker->name,
                'email'            => $this->faker->email,
                'phone'            => $this->faker->phoneNumber,
                'additional_phone' => $this->faker->phoneNumber,
                'verify_call'      => rand(0, 1),
                'total'            => rand(1000, 10000),
                'delivery_date'    => $this->faker->date('d-m-Y'),
                'delivery_time'    => $delivery_times[rand(0, count($delivery_times) - 1)],
                'city_id'          => $city_id,
                'city'             => $city_id ? null : $this->faker->city,
                'address'          => $this->faker->address,
                'comment'          => $this->getLocalizedFaker()->realText(rand(50, 100)),
            ];
            
            $order = new Order($input);
            $order->save();
            
            $basket = WeeklyMenuBasket::with('recipes')->get()->random(1);
            if (count($basket)) {
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
            
            $ingredients_count = Ingredient::get()->count();
            $ingredients = Ingredient::get()->random(
                rand($ingredients_count > 2 ? 2 : $ingredients_count, $ingredients_count)
            );
            if ($ingredients_count) {
                foreach ($ingredients as $ingredient) {
                    OrderIngredient::create(
                        [
                            'order_id'      => $order->id,
                            'ingredient_id' => $ingredient->id,
                            'name'          => $ingredient->name,
                            'count'         => rand(1, 10),
                        ]
                    );
                }
            }

            $baskets_count = Basket::additional()->count();
            if ($baskets_count) {
                $baskets = Basket::additional()->get()->random(rand(1, $baskets_count))->pluck('id')->toArray();
                if (count($baskets)) {
                    $order->baskets()->sync($baskets);
                }
            }
        }
    }
}
