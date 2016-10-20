<?php

use App\Models\Basket;
use App\Models\BasketRecipe;
use App\Models\City;
use App\Models\Group;
use App\Models\Order;
use App\Models\OrderBasket;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\RecipeIngredient;
use App\Models\WeeklyMenu;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class _OrderSeeder
 */
class _OrdersSeeder extends DataSeeder
{
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * _OrdersSeeder constructor.
     *
     * @param \App\Services\OrderService $orderService
     */
    public function __construct(\App\Services\OrderService $orderService)
    {
        parent::__construct();
        
        $this->orderService = $orderService;
    }
    
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
            $city_id = rand(0, 1) > 0 ? City::all()->random(1)->id : null;
            
            if ($i % 2 == 0) {
                $delivery_date = Carbon::now()->endOfWeek()->startOfDay()->format('d-m-Y');
            } else {
                $delivery_date = Carbon::now()->endOfWeek()->addDay()->startOfDay()->format('d-m-Y');
            }
            
            $input = [
                'user_id'          => Group::clients()->first()->users()->get()->random(1)->id,
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
                    
                    $main_basket = new OrderBasket(
                        [
                            'weekly_menu_basket_id' => $basket->id,
                            'price'                 => $basket->getPriceInOrder(),
                            'name'                  => $basket->getName(),
                        ]
                    );
                    
                    $order->main_basket()->save($main_basket);
                }
                
                $recipes = $basket->recipes->pluck('recipe_id')->toArray();
                $ingredients = RecipeIngredient::with('ingredient')
                    ->joinIngredient()
                    ->home()
                    ->whereIn('recipe_id', $recipes)
                    ->where('ingredients.sale_price', '>', 0)
                    ->whereNotNull('ingredients.sale_unit_id')
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
                                'price'            => $ingredients->ingredient->sale_price,
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
                                    'price'            => $ingredient->ingredient->sale_price,
                                ]
                            );
                        }
                    }
                }
            }
            
            $baskets_count = Basket::additional()->count();
            if ($baskets_count) {
                $baskets = Basket::additional()->get()
                    ->random(rand(1, ($baskets_count < 2 ? $baskets_count : 2)));
                
                if ($baskets instanceof Collection) {
                    foreach ($baskets as $basket) {
                        $_basket = new OrderBasket(
                            [
                                'basket_id' => $basket->id,
                                'price'     => $basket->getPrice(),
                                'name'      => $basket->getName(),
                            ]
                        );
                        
                        $order->additional_baskets()->save($_basket);
                    }
                } else {
                    $_basket = new OrderBasket(
                        [
                            'basket_id' => $baskets->id,
                            'price'     => $baskets->getPrice(),
                            'name'      => $baskets->getName(),
                        ]
                    );
                    
                    $order->additional_baskets()->save($_basket);
                }
            }
    
            $this->orderService->updatePrices($order);
    
            list($subtotal, $total) = $this->orderService->getTotals($order);
    
            $order->subtotal = $subtotal;
            $order->total = $total;
            
            $order->save();
        }
    }
}
