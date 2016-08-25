<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 18.08.16
 * Time: 9:56
 */

namespace App\Services;

use App\Models\Basket;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\Purchase;
use App\Models\RecipeIngredient;
use DB;
use Excel;
use Illuminate\Support\Collection;

/**
 * Class PackagingService
 * @package App\Services
 */
class PackagingService
{
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return array
     */
    public function repackagingForWeek($year, $week)
    {
        $ingredients = $this->_getRepackagingIngredients($year, $week);
        
        $categories = $this->_buildCategories($ingredients);
        
        return $categories;
    }
    
    /**
     * @param $year
     * @param $week
     *
     * @return Collection
     */
    public function recipesForWeek($year, $week)
    {
        $orders = Order::ofStatus('processed')->forWeek($year, $week)->get(['id'])->pluck('id');
        
        $recipes = $this->_getOrderedRecipes($orders);
        $this->_addIngredients($recipes);
        $this->_addOrderedIngredients($orders, $recipes);
        
        $recipes = new Collection($recipes);
        $recipes = $recipes->sortByDesc(
            function ($recipe) {
                return $recipe['recipes_count'];
            }
        );
        
        return $recipes;
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return array
     */
    public function stickersForWeek($year, $week)
    {
        $orders = Order::ofStatus('processed')->forWeek($year, $week)->get(['id'])->pluck('id');
        
        $recipes = $this->_getOrderedRecipes($orders);
        $this->_addOrderedIngredients($orders, $recipes);
        
        $baskets = [];
        
        foreach ($recipes as $key => $recipe) {
            $basket_id = $recipe['basket_id'].'_'.$recipe['portions'];
            
            if (!isset($baskets[$basket_id])) {
                $baskets[$basket_id] = [
                    'name'      => $recipe['basket_name'],
                    'basket_id' => $recipe['basket_id'],
                    'portions'  => $recipe['portions'],
                    'recipes'   => [],
                ];
            }
            
            if (!isset($baskets[$basket_id]['recipes'][$recipe['basket_recipe_id']])) {
                $ingredients = empty($recipe['ingredients']) ? [] : $recipe['ingredients'];
                
                $baskets[$basket_id]['recipes'][$recipe['basket_recipe_id']] = [
                    'name'          => $recipe['name'],
                    'recipe_id'     => $recipe['recipe_id'],
                    'position'      => $recipe['position'],
                    'recipes_count' => $recipe['recipes_count'] - (count($ingredients)),
                    'ingredients'   => $ingredients,
                ];
            }
            
            unset($recipes[$key]);
        }
        
        return $baskets;
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return array
     */
    public function usersForWeek($year, $week)
    {
        $users = [];
        
        $orders = Order::with('user', 'city', 'recipes', 'ingredients', 'ingredients.recipe', 'baskets')
            ->ofStatus('processed')
            ->forWeek($year, $week)
            ->get();
        
        foreach ($orders as $order) {
            if (!isset($users[$order->user_id])) {
                $users[$order->user_id] = [
                    'user_id'     => $order->user_id,
                    'full_name'   => $order->user->getFullName(),
                    'address'     => $order->getFullAddress(),
                    'comment'     => $order->comment,
                    'recipes'     => [],
                    'ingredients' => [],
                    'baskets'     => [],
                ];
            }
            
            foreach ($order->recipes as $recipe) {
                $users[$order->user_id]['recipes'][] = [
                    'name' => $recipe->recipe->getName(),
                ];
            }
            
            foreach ($order->ingredients()->orderBy('basket_recipe_id')->get() as $ingredient) {
                $users[$order->user_id]['ingredients'][] = [
                    'name'      => $ingredient->name,
                    'count'     => $ingredient->count,
                    'unit'      => $ingredient->ingredient->unit->name,
                    'repacking' => $ingredient->ingredient->repacking,
                    'recipe'    => $ingredient->recipe->getName(),
                ];
            }
            
            foreach ($order->baskets as $basket) {
                $users[$order->user_id]['baskets'][] = [
                    'name' => $basket->name,
                ];
            }
        }
        
        return $users;
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return array
     */
    public function deliveriesForWeek($year, $week)
    {
        $days = [];
        
        $orders = Order::with('user', 'user.orders', 'city', 'recipes', 'baskets')
            ->ofStatus('processed')
            ->forWeek($year, $week)
            ->orderBy('delivery_date')
            ->get();
        
        foreach ($orders as $order) {
            if (!isset($days[$order->delivery_date])) {
                $days[$order->delivery_date] = [];
            }
            
            $days[$order->delivery_date][] = $order;
        }
        
        return $days;
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return mixed
     */
    public function downloadRepackaging($year, $week)
    {
        $data = $this->repackagingForWeek($year, $week);
        
        return Excel::create(
            trans('labels.tab_repackaging').' '.trans('labels.w_label').$week.', '.$year,
            function ($excel) use ($data, $year, $week) {
                $excel->sheet(
                    trans('labels.ingredients_which_need_repackaging'),
                    function ($sheet) use ($data) {
                        $sheet->loadView('views.packaging.download.repackaging')->with('list', $data);
                    }
                );
            }
        )->download('xls');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return mixed
     */
    public function downloadRecipes($year, $week)
    {
        $data = $this->recipesForWeek($year, $week);
        
        return Excel::create(
            trans('labels.tab_packaging_recipes').' '.trans('labels.w_label').$week.', '.$year,
            function ($excel) use ($data, $year, $week) {
                foreach ($data as $recipe) {
                    $sheet = preg_replace('/['.preg_quote(':*?""<>|~!@#$%^&=`').']/', '_', $recipe['name']);
                    $sheet = str_replace(['\\', '/'], '_', $sheet);
                    str_limit($sheet, 31, '');
                    
                    $excel->sheet(
                        $sheet,
                        function ($sheet) use ($recipe) {
                            $sheet->loadView('views.packaging.download.recipes')->with('recipe', $recipe);
                        }
                    );
                }
            }
        )->download('xls');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return mixed
     */
    public function downloadStickers($year, $week)
    {
        $list = $this->stickersForWeek($year, $week);
        
        return Excel::create(
            trans('labels.stickers').' '.trans('labels.w_label').$week.', '.$year,
            function ($excel) use ($list, $year, $week) {
                $excel->sheet(
                    trans('labels.stickers'),
                    function ($sheet) use ($list) {
                        $sheet->loadView('views.packaging.download.stickers')->with('list', $list);
                    }
                );
            }
        )->download('xls');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return mixed
     */
    public function downloadUsers($year, $week)
    {
        $data = $this->usersForWeek($year, $week);
        
        return Excel::create(
            trans('labels.tab_packaging_users').' '.trans('labels.w_label').$week.', '.$year,
            function ($excel) use ($data, $year, $week) {
                $excel->sheet(
                    trans('labels.users'),
                    function ($sheet) use ($data) {
                        $sheet->loadView('views.packaging.download.users')->with('list', $data);
                    }
                );
            }
        )->download('xls');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return mixed
     */
    public function downloadDeliveries($year, $week)
    {
        $data = $this->deliveriesForWeek($year, $week);
        
        return Excel::create(
            trans('labels.tab_packaging_deliveries').' '.trans('labels.w_label').$week.', '.$year,
            function ($excel) use ($data, $year, $week) {
                $excel->sheet(
                    trans('labels.deliveries'),
                    function ($sheet) use ($data) {
                        $sheet->loadView('views.packaging.download.deliveries')->with('list', $data);
                    }
                );
            }
        )->download('xls');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return array
     */
    private function _getRepackagingIngredients($year, $week)
    {
        $ingredients = Purchase::with('ingredient', 'ingredient.category')
            ->JoinIngredient()
            ->JoinIngredientCategory()
            ->forWeek($year, $week)
            ->where('ingredients.repacking', true)
            ->orderBy('categories.position')
            ->orderBy('ingredients.name')
            ->get();
        
        return $ingredients;
    }
    
    /**
     * @param array $orders
     *
     * @return array
     */
    private function _getOrderedRecipes($orders)
    {
        $recipes = OrderRecipe::whereIn('order_id', $orders)
            ->joinBasketRecipe()
            ->joinWeeklyMenuBasket()
            ->joinBasket()
            ->joinRecipe()
            ->select(
                DB::raw('weekly_menu_baskets.basket_id as basket_id'),
                DB::raw('baskets.type as basket_type'),
                DB::raw('baskets.name as basket_name'),
                DB::raw('order_recipes.basket_recipe_id as basket_recipe_id'),
                'recipes.name',
                'basket_recipes.recipe_id',
                'basket_recipes.position',
                DB::raw('weekly_menu_baskets.portions as portions'),
                DB::raw('count(order_recipes.basket_recipe_id) as recipes_count')
            )
            ->groupBy('basket_recipes.recipe_id')
            ->orderBy('recipes.name')
            ->get()
            ->keyBy('recipe_id')
            ->toArray();
        
        $_baskets = Basket::additional()->joinBasketOrder()->joinOrders()
            ->with('recipes')
            ->whereIn('orders.id', $orders)
            ->select('baskets.id', 'baskets.name', DB::raw('count(basket_id) as baskets_count'))
            ->groupBy('basket_order.basket_id')
            ->get();
        
        foreach ($_baskets as $basket) {
            foreach ($basket->recipes as $recipe) {
                if (!isset($recipes[$recipe->recipe_id])) {
                    $recipes[$recipe->recipe_id] = [
                        'basket_id'        => $basket->id,
                        'basket_type'      => $basket->type,
                        'basket_name'      => $basket->name,
                        'basket_recipe_id' => $recipe->id,
                        'recipe_id'        => $recipe->recipe_id,
                        'name'             => $recipe->recipe->name,
                        'position'         => $recipe->position,
                        'portions'         => $recipe->recipe->portions,
                        'recipes_count'    => 0,
                    ];
                }
                
                $recipes[$recipe->recipe_id]['recipes_count'] += $basket->baskets_count;
            }
        }
        
        return $recipes;
    }
    
    /**
     * @param array|Collection $recipes
     */
    private function _addIngredients(&$recipes)
    {
        foreach ($recipes as $key => $recipe) {
            $recipes[$key]['packages'] = [
                1 => [],
                2 => [],
            ];
            
            $ingredients = Ingredient::joinRecipeIngredients()->joinCategory()->joinUnit()->joinParameters()
                ->where('recipe_ingredients.recipe_id', $recipe['recipe_id'])
                ->where('recipe_ingredients.type', RecipeIngredient::getTypeIdByName('normal'))
                ->orderBy('parameters.package')
                ->orderBy('parameters.position')
                ->orderBy('ingredients.name')
                ->get(
                    [
                        'ingredients.id',
                        'ingredients.name',
                        'ingredients.category_id',
                        'ingredients.unit_id',
                        'ingredients.repacking',
                        'recipe_ingredients.count',
                        'parameters.package',
                        'parameters.position',
                        DB::raw('categories.name as category_name'),
                        DB::raw('units.name as unit_name'),
                        DB::raw('parameters.name as parameter_name'),
                    ]
                )
                ->toArray();
            
            foreach ($ingredients as $ingredient) {
                $recipes[$key]['packages'][$ingredient['package']][$ingredient['id']] = $ingredient;
                
                $recipes[$key]['packages'][$ingredient['package']][$ingredient['id']]['total'] = $ingredient['count'] * $recipe['recipes_count'];
            }
        }
    }
    
    /**
     * @param array $orders
     * @param array $recipes
     */
    private function _addOrderedIngredients($orders, &$recipes)
    {
        $ingredients = OrderIngredient::with('ingredient')->joinBasketRecipe()
            ->joinIngredient()->joinIngredientCategory()->joinIngredientUnit()->joinIngredientParameters()
            ->whereIn('order_id', $orders)
            ->orderBy('parameters.package')
            ->orderBy('parameters.position')
            ->orderBy('ingredients.name')
            ->get(
                [
                    'order_ingredients.order_id',
                    'order_ingredients.basket_recipe_id',
                    'order_ingredients.ingredient_id',
                    'order_ingredients.count',
                    'ingredients.id',
                    'ingredients.name',
                    'ingredients.category_id',
                    'ingredients.unit_id',
                    'ingredients.repacking',
                    'basket_recipes.recipe_id',
                    'parameters.package',
                    'parameters.position',
                    DB::raw('categories.name as category_name'),
                    DB::raw('units.name as unit_name'),
                    DB::raw('parameters.name as parameter_name'),
                ]
            );
        
        foreach ($ingredients as $ingredient) {
            if (!isset($recipes[$ingredient->recipe_id]['ingredients'])) {
                $recipes[$ingredient->recipe_id]['ingredients'] = [];
            }
            
            if (!isset($recipes[$ingredient->recipe_id]['ingredients'][$ingredient->order_id])) {
                $recipes[$ingredient->recipe_id]['ingredients'][$ingredient->order_id] = [];
            }
            
            $recipes[$ingredient->recipe_id]['ingredients'][$ingredient->order_id][] = $ingredient;
        }
    }
    
    /**
     * @param array $ingredients
     *
     * @return array
     */
    private function _buildCategories($ingredients)
    {
        $categories = [];
        
        foreach ($ingredients as $ingredient) {
            if (!isset($categories[$ingredient->ingredient->category_id])) {
                $categories[$ingredient->ingredient->category_id] = [
                    'name'        => $ingredient->ingredient->category->name,
                    'position'    => $ingredient->ingredient->category->position,
                    'ingredients' => [],
                ];
            }
            
            if (!isset($categories[$ingredient->ingredient->category_id]['ingredients'][$ingredient->ingredient_id])) {
                $categories[$ingredient->category_id]['ingredients'][$ingredient->ingredient_id] = [
                    'name'  => $ingredient->ingredient->name,
                    'unit'  => $ingredient->ingredient->unit->name,
                    'count' => 0,
                ];
            }
            
            $categories[$ingredient->ingredient->category_id]['ingredients'][$ingredient->ingredient_id]['count'] = $ingredient->count;
        }
        
        return $categories;
    }
}