<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 18.08.16
 * Time: 9:56
 */

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderBasket;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
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
     * @return \Illuminate\Support\Collection
     */
    public function repackagingForWeek($year, $week)
    {
        $orders = $this->_getOrders($year, $week)->pluck('id');
        
        $recipes = $this->_getOrderedRecipes($orders);
        
        $ingredients = $this->_getIngredients($recipes);
        
        $ordered_ingredients = $this->_getOrderedIngredients($orders);
        
        $ingredients = array_merge($ingredients, $ordered_ingredients);
        
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
        $orders = $this->_getOrders($year, $week)->pluck('id');
        
        $recipes = $this->_getOrderedRecipes($orders);
        $this->_addIngredients($recipes);
        $this->_addOrderedIngredients($orders, $recipes);
        
        $recipes = new Collection($recipes);
        
        $recipes = $recipes->sortBy('name');
        
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
        $orders = $this->_getOrders($year, $week)->pluck('id');
        
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
        
        foreach ($baskets as $key => $basket) {
            $recipes = collect($basket['recipes'])->sortBy(
                function ($item) {
                    return $item['position'].' '.$item['name'];
                }
            );
            
            $baskets[$key]['recipes'] = $recipes;
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
        
        $orders = $this->_getOrders(
            $year,
            $week,
            ['user', 'city', 'recipes', 'ingredients', 'ingredients.recipe', 'additional_baskets']
        );
        
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
                if (!empty($recipe->recipe)) {
                    $users[$order->user_id]['recipes'][] = [
                        'name' => $recipe->recipe->getName(),
                    ];
                }
            }
            
            foreach ($order->ingredients()->orderBy('basket_recipe_id')->get() as $ingredient) {
                $users[$order->user_id]['ingredients'][] = [
                    'name'      => $ingredient->name,
                    'count'     => $ingredient->count,
                    'unit'      => $ingredient->ingredient->sale_unit->name,
                    'repacking' => $ingredient->ingredient->repacking,
                    'recipe'    => $ingredient->recipe->getName(),
                ];
            }
            
            foreach ($order->additional_baskets as $basket) {
                $users[$order->user_id]['baskets'][] = [
                    'name' => $basket->getName(),
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
        
        $orders = $this->_getOrders(
            $year,
            $week,
            ['user', 'user.orders', 'city', 'recipes', 'main_basket', 'additional_baskets']
        )
            ->sortBy('delivery_date');
        
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
            $this->_getFileName($year, $week, 'repackaging'),
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
            $this->_getFileName($year, $week, 'packaging_recipes'),
            function ($excel) use ($data, $year, $week) {
                foreach ($data as $recipe) {
                    $sheet = get_excel_sheet_name($recipe['name']);
                    $sheet = str_limit($sheet, 26, '').'...-'.$recipe['portions'];
                    
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
            $this->_getFileName($year, $week, 'stickers'),
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
            $this->_getFileName($year, $week, 'packaging_users'),
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
            $this->_getFileName($year, $week, 'packaging_deliveries'),
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
     * @param Collection $orders
     *
     * @return array
     */
    private function _getOrderedRecipes($orders)
    {
        $recipes = [];
        
        $_recipes = OrderRecipe::whereIn('order_id', $orders)
            ->with('recipe')
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
            ->get();
        
        foreach ($_recipes as $recipe) {
            if (!empty($recipe->recipe)) {
                $name = $recipe->recipe->getName();
                
                $recipes[$recipe->recipe_id] = $recipe->toArray();
                $recipes[$recipe->recipe_id]['name'] = $name;
            }
        }
        
        unset($_recipes);
        
        $_baskets = OrderBasket::additional()
            ->joinBasket()
            ->with('basket', 'basket.recipes')
            ->whereIn('order_baskets.order_id', $orders)
            ->select(
                'order_baskets.basket_id',
                DB::raw('count(order_baskets.basket_id) as baskets_count')
            )
            ->groupBy('order_baskets.basket_id')
            ->get();
        
        foreach ($_baskets as $basket) {
            foreach ($basket->basket->recipes as $recipe) {
                if (!isset($recipes[$recipe->recipe_id])) {
                    $recipes[$recipe->recipe_id] = [
                        'basket_id'        => $basket->basket_id,
                        'basket_type'      => $basket->basket->type,
                        'basket_name'      => $basket->getName(),
                        'basket_recipe_id' => $recipe->id,
                        'recipe_id'        => $recipe->recipe_id,
                        'name'             => $recipe->getName(),
                        'position'         => 0,
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
                $package = empty($ingredient['package']) ? 1 : $ingredient['package'];
                
                $recipes[$key]['packages'][$package][$ingredient['id']] = $ingredient;
                
                $recipes[$key]['packages'][$package][$ingredient['id']]['total'] = $ingredient['count'] * $recipe['recipes_count'];
            }
        }
    }
    
    /**
     * @param Collection $orders
     * @param array      $recipes
     */
    private function _addOrderedIngredients($orders, &$recipes)
    {
        $ingredients = OrderIngredient::with('ingredient')->joinBasketRecipe()
            ->joinIngredient()->joinIngredientCategory()->joinIngredientSaleUnit()->joinIngredientParameters()
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
            $ingredient['package'] = empty($ingredient['package']) ? 1 : $ingredient['package'];
            
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
     * @param array $recipes
     *
     * @return array
     */
    private function _getIngredients($recipes = [])
    {
        $ingredients = [];
        
        $_ingredients = RecipeIngredient::with('ingredient', 'ingredient.category')
            ->joinIngredient()
            ->joinIngredientCategory()
            ->whereIn('recipe_ingredients.recipe_id', array_keys($recipes))
            ->where('recipe_ingredients.type', RecipeIngredient::getTypeIdByName('normal'))
            ->where('ingredients.repacking', true)
            ->groupBy('recipe_ingredients.count')
            ->get(
                [
                    'ingredients.id',
                    'ingredients.unit_id',
                    'ingredients.category_id',
                    'recipe_ingredients.ingredient_id',
                    'recipe_ingredients.recipe_id',
                    'recipe_ingredients.type',
                    'ingredients.name',
                    DB::raw('categories.name as category_name'),
                    DB::raw('categories.position as category_position'),
                    'categories.position',
                    'recipe_ingredients.count',
                ]
            );
        
        foreach ($_ingredients as $ingredient) {
            $id = $ingredient->id.'_'.$ingredient->type;
            
            if (!isset($ingredients[$id])) {
                $ingredients[$id] = [
                    'name'              => $ingredient->name,
                    'category_id'       => $ingredient->category_id,
                    'category_name'     => $ingredient->category_name,
                    'category_position' => $ingredient->category_position,
                    'package'           => $ingredient->count,
                    'count'             => 0,
                    'unit_name'         => $ingredient->ingredient->unit->name,
                ];
            }
            
            $ingredients[$id]['count'] += $recipes[$ingredient->recipe_id]['recipes_count'];
        }
        
        return $ingredients;
    }
    
    /**
     * @param Collection $orders
     *
     * @return array
     */
    private function _getOrderedIngredients($orders)
    {
        $ingredients = [];
        $type = RecipeIngredient::getTypeIdByName('home');
        
        $_ingredients = OrderIngredient::with('ingredient', 'ingredient.category')
            ->joinIngredient()
            ->joinIngredientCategory()
            ->whereIn('order_id', $orders)
            ->where('ingredients.repacking', true)
            ->groupBy('order_ingredients.count')
            ->get(
                [
                    'ingredients.id',
                    'ingredients.sale_unit_id',
                    'ingredients.category_id',
                    'order_ingredients.ingredient_id',
                    'ingredients.name',
                    DB::raw('categories.name as category_name'),
                    DB::raw('categories.position as category_position'),
                    'categories.position',
                    'order_ingredients.count',
                ]
            );
        
        foreach ($_ingredients as $ingredient) {
            $id = $ingredient->id.'_'.$type;
            
            if (!isset($ingredients[$id])) {
                $ingredients[$id] = [
                    'name'              => $ingredient->name,
                    'category_id'       => $ingredient->category_id,
                    'category_name'     => $ingredient->category_name,
                    'category_position' => $ingredient->category_position,
                    'package'           => $ingredient->count,
                    'count'             => 0,
                    'unit_name'         => $ingredient->ingredient->sale_unit->name,
                ];
            }
            
            $ingredients[$id]['count']++;
        }
        
        return $ingredients;
    }
    
    /**
     * @param array $ingredients
     *
     * @return \Illuminate\Support\Collection
     */
    private function _buildCategories($ingredients)
    {
        $categories = [];
        
        foreach ($ingredients as $ingredient) {
            if (!isset($categories[$ingredient['category_id']])) {
                $categories[$ingredient['category_id']] = [
                    'name'        => $ingredient['category_name'],
                    'position'    => $ingredient['category_position'],
                    'ingredients' => [],
                ];
            }
            
            $categories[$ingredient['category_id']]['ingredients'][] = $ingredient;
        }
        
        $categories = collect($categories)->sort(
            function ($a, $b) {
                return strnatcmp($a['position'], $b['position'])
                    ? : strnatcmp($a['name'], $b['name']);
            }
        );
        
        return $categories;
    }
    
    /**
     * @param int        $year
     * @param int        $week
     * @param array|null $with
     *
     * @return array|Collection
     */
    private function _getOrders($year, $week, $with = [])
    {
        $status = 'processed';
        
        if (past_week($year, $week)) {
            $status = 'archived';
        }
        
        if (before_week_closing($year, $week)) {
            $status = 'paid';
        }
        
        return Order::ofStatus($status)->with($with)->forWeek($year, $week)->get();
    }
    
    /**
     * @param int    $year
     * @param int    $week
     * @param string $type
     *
     * @return string
     */
    private function _getFileName($year, $week, $type)
    {
        $file_name = trans('labels.tab_'.$type).' '.trans('labels.w_label').$week.', '.$year;
        
        if (before_finalisation($year, $week)) {
            return trans('labels.this_is_not_final_version').'. '.$file_name;
        }
        
        return $file_name;
    }
}