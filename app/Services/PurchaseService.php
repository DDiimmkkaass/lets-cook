<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.08.16
 * Time: 15:13
 */

namespace App\Services;

use App\Models\Basket;
use App\Models\BasketRecipe;
use App\Models\Order;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\Purchase;
use App\Models\RecipeIngredient;
use App\Models\Supplier;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Support\Collection;

/**
 * Class PurchaseService
 * @package App\Services
 */
class PurchaseService
{
    
    /**
     * @return void
     */
    public function generate()
    {
        $list = [
            'year' => Carbon::now()->startOfWeek()->year,
            'week' => Carbon::now()->startOfWeek()->weekOfYear,
        ];
        
        $list['suppliers'] = $this->_getSuppliers();
        
        $this->_processIngredients($list);
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return void
     */
    public function generateFromMenu($year, $week)
    {
        $list = [
            'year' => $year,
            'week' => $week,
        ];
        
        $recipes = $this->_getRecipesFromWeeklyMenu($list);
        
        $ingredients = $this->_getIngredients($recipes);
        
        $ingredients->map(
            function ($item, $index) {
                return $item->count = 0;
            }
        );
        
        $list['suppliers'] = $this->_buildSuppliersTable($ingredients, null);
        
        $this->_processIngredients($list);
    }
    
    /**
     * @param int|null $year
     * @param int|null $week
     *
     * @return array
     */
    public function preGenerate($year, $week)
    {
        $list = [
            'year'       => $year,
            'week'       => $week,
            'categories' => [],
        ];
        
        $list['suppliers'] = $this->_getSuppliers($list);
        
        return $this->_processPreGenerationIngredients($list);
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return array
     */
    public function getForWeek($year, $week)
    {
        $list = [
            'year'       => $year,
            'week'       => $week,
            'suppliers'  => [],
            'categories' => [],
        ];
        
        $purchases = Purchase::with('ingredient', 'ingredient.category', 'ingredient.supplier', 'ingredient.sale_unit')
            ->joinIngredient()
            ->joinIngredientSupplier()
            ->joinIngredientCategory()
            ->where('year', $list['year'])
            ->where('week', $list['week'])
            ->orderBy('suppliers.priority')
            ->orderBy('categories.position')
            ->orderBy('ingredients.name')
            ->get(['purchases.*'])
            ->keyBy(
                function ($item) {
                    return $item->ingredient_id.'_'.$item->type;
                }
            );
        
        foreach ($purchases as $key => $ingredient) {
            $_ingredient = $ingredient->ingredient;
            
            if (!isset($list['suppliers'][$_ingredient->supplier_id])) {
                $list['suppliers'][$_ingredient->supplier_id] = [
                    'name'       => $_ingredient->supplier->name,
                    'position'   => $_ingredient->supplier->priority,
                    'categories' => [],
                ];
            }
            
            if ($ingredient->purchase_manager) {
                if (!isset($list['categories'][$_ingredient->category_id])) {
                    $list['categories'][$_ingredient->category_id] = [
                        'name'        => $_ingredient->category->name,
                        'position'    => $_ingredient->category->position,
                        'ingredients' => [],
                    ];
                }
                
                $list['categories'][$_ingredient->category_id]['ingredients'][$key] = $ingredient;
            } else {
                if (!isset($list['suppliers'][$_ingredient->supplier_id]['categories'][$_ingredient->category_id])) {
                    $list['suppliers'][$_ingredient->supplier_id]['categories'][$_ingredient->category_id] = [
                        'name'        => $_ingredient->category->name,
                        'position'    => $_ingredient->category->position,
                        'ingredients' => [],
                    ];
                }
                
                $list['suppliers'][$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$key] = $ingredient;
            }
        }
        
        return $list;
    }
    
    /**
     * @param int      $year
     * @param int      $week
     * @param int|bool $supplier_id
     * @param bool     $pre_report
     *
     * @return
     */
    public function download($year, $week, $supplier_id = false, $pre_report = false)
    {
        $file_name = $this->_getDownloadFileName($year, $week, $supplier_id, $pre_report);
        $sheet_name = $this->_getSheetTabName($supplier_id, $pre_report);
        $view = $this->_getViewName($supplier_id);
        
        $list = $this->_getPurchaseFor($year, $week, $supplier_id);
        
        return Excel::create(
            $file_name,
            function ($excel) use ($list, $view, $sheet_name, $pre_report) {
                $excel->sheet(
                    get_excel_sheet_name($sheet_name),
                    function ($sheet) use ($list, $view, $sheet_name, $pre_report) {
                        $sheet->loadView('views.purchase.partials.'.$view)
                            ->with(['list' => $list, 'title' => $sheet_name, 'pre_report' => $pre_report]);
                    }
                );
            }
        )->download('xls');
    }
    
    /**
     * @param bool $pre_generation
     *
     * @return array
     */
    private function _getSuppliers($pre_generation = false)
    {
        if ($pre_generation) {
            $orders = Order::notOfStatus(['archived', 'deleted']);
        } else {
            $orders = Order::ofStatus('processed');
        }
        $orders = $orders->forCurrentWeek()->get(['id'])->pluck('id');
        
        $recipes = $this->_getRecipes($orders);
        
        $ingredients = $this->_getIngredients($recipes);
        
        $suppliers = $this->_buildSuppliersTable($ingredients, $recipes);
        
        $this->_addOrderIngredients($suppliers, $orders);
        
        return $suppliers;
    }
    
    /**
     * @param array $orders
     *
     * @return array
     */
    private function _getRecipes($orders)
    {
        $recipes = OrderRecipe::whereIn('order_id', $orders)
            ->joinBasketRecipe()
            ->select('recipe_id', DB::raw('count(recipe_id) as recipes_count'))
            ->groupBy('recipe_id')
            ->get()
            ->keyBy('recipe_id')
            ->toArray();
        
        $_baskets = Basket::additional()->joinBasketOrder()->joinOrders()
            ->with('recipes')
            ->whereIn('orders.id', $orders)
            ->select('baskets.id', DB::raw('count(basket_id) as baskets_count'))
            ->groupBy('basket_order.basket_id')
            ->get();
        
        foreach ($_baskets as $basket) {
            foreach ($basket->recipes as $recipe) {
                if (!isset($recipes[$recipe->recipe_id])) {
                    $recipes[$recipe->recipe_id] = [
                        'recipe_id'     => $recipe->recipe_id,
                        'recipes_count' => 0,
                    ];
                }
                
                $recipes[$recipe->recipe_id]['recipes_count'] += $basket->baskets_count;
            }
        }
        
        return $recipes;
    }
    
    /**
     * @param array $week
     *
     * @return array
     */
    private function _getRecipesFromWeeklyMenu($week)
    {
        $recipes = BasketRecipe::joinWeeklyMenuBasket()->joinWeeklyMenu()
            ->where('weekly_menus.year', $week['year'])
            ->where('weekly_menus.week', $week['week'])
            ->select('recipe_id', DB::raw('count(recipe_id) as recipes_count'))
            ->groupBy('recipe_id')
            ->get()
            ->keyBy('recipe_id')
            ->toArray();
        
        return $recipes;
    }
    
    /**
     * @param array $recipes
     *
     * @return Collection
     */
    private function _getIngredients($recipes = [])
    {
        $ingredients = RecipeIngredient::with('ingredient', 'ingredient.category', 'ingredient.supplier')
            ->joinIngredient()
            ->joinIngredientSupplier()
            ->joinIngredientCategory()
            ->whereIn('recipe_id', array_keys($recipes))
            ->where('recipe_ingredients.type', RecipeIngredient::getTypeIdByName('normal'))
            ->orderBy('suppliers.priority')
            ->orderBy('categories.position')
            ->orderBy('ingredients.name')
            ->get();
        
        return $ingredients;
    }
    
    /**
     * @param array $suppliers
     * @param array $orders
     */
    private function _addOrderIngredients(&$suppliers, $orders)
    {
        $ingredients = OrderIngredient::with(
            'ingredient',
            'ingredient.category',
            'ingredient.supplier'
        )
            ->joinIngredient()
            ->joinIngredientSupplier()
            ->joinIngredientCategory()
            ->whereIn('order_id', $orders)
            ->orderBy('suppliers.priority')
            ->orderBy('categories.position')
            ->orderBy('ingredients.name')
            ->get();
        
        $suppliers = $this->_buildSuppliersTable($ingredients, [], $suppliers, true);
    }
    
    /**
     * @param Collection $ingredients
     * @param array      $recipes
     * @param array|null $suppliers
     * @param bool       $order_ingredients
     *
     * @return array
     */
    private function _buildSuppliersTable($ingredients, $recipes, $suppliers = null, $order_ingredients = false)
    {
        $suppliers = $suppliers ? $suppliers : [];
        
        foreach ($ingredients as $ingredient) {
            $_ingredient = $ingredient->ingredient;
            
            $type = $order_ingredients ? Purchase::getTypeIdByName('order') : Purchase::getTypeIdByName('recipe');
            $_ingredient_id = $_ingredient->id.'_'.$type;
            
            if (!isset($suppliers[$_ingredient->supplier_id])) {
                $suppliers[$_ingredient->supplier_id] = [
                    'name'       => $_ingredient->supplier->name,
                    'position'   => $_ingredient->supplier->priority,
                    'categories' => [],
                ];
            }
            
            if (!isset($suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id])) {
                $suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id] = [
                    'name'        => $_ingredient->category->name,
                    'position'    => $_ingredient->category->position,
                    'ingredients' => [],
                ];
            }
            
            $count = empty($recipes) ? $ingredient->count : $ingredient->count * $recipes[$ingredient->recipe_id]['recipes_count'];
            
            if (!isset($suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$_ingredient_id])) {
                $data = [
                    'ingredient_id' => $ingredient->ingredient_id,
                    'supplier_id'   => $_ingredient->supplier_id,
                    'type'          => $type,
                    'name'          => $_ingredient->name,
                    'price'         => $_ingredient->price,
                    'unit'          => $order_ingredients ? $_ingredient->sale_unit->name : $_ingredient->unit->name,
                    'count'         => $count,
                ];
                
                $suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$_ingredient_id] = $data;
            } else {
                $suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$_ingredient_id]['count'] += $count;
            }
        }
        
        return $suppliers;
    }
    
    /**
     * @param array $list
     */
    private function _processIngredients($list)
    {
        $ingredients = [];
        
        $exists_purchases = Purchase::with('ingredient')
            ->where('year', $list['year'])
            ->where('week', $list['week'])
            ->get()
            ->keyBy(
                function ($item) {
                    return $item->ingredient_id.'_'.$item->type;
                }
            );
        
        foreach ($list['suppliers'] as $supplier_id => $supplier) {
            foreach ($supplier['categories'] as $category_id => $category) {
                foreach ($category['ingredients'] as $key => $ingredient) {
                    if (!$exists_purchases->has($key)) {
                        $purchase = new Purchase(
                            array_merge(
                                $ingredient,
                                [
                                    'year' => $list['year'],
                                    'week' => $list['week'],
                                ]
                            )
                        );
                        
                        $purchase->save();
                    } else {
                        $purchase = $exists_purchases->get($key);
                        
                        $purchase->fill($ingredient);
                        
                        if ($purchase->isDirty()) {
                            $purchase->save();
                        }
                    }
                    
                    if (!isset($ingredients[$ingredient['type']])) {
                        $ingredients[$ingredient['type']] = [];
                    }
                    
                    $ingredients[$ingredient['type']][] = $ingredient['ingredient_id'];
                }
            }
        }
        
        foreach ($ingredients as $type => $_ingredients) {
            Purchase::where('week', $list['week'])->where('year', $list['year'])
                ->whereType($type)
                ->whereNotIn('ingredient_id', $_ingredients)
                ->delete();
        }
    }
    
    /**
     * @param array $list
     *
     * @return array
     */
    private function _processPreGenerationIngredients($list)
    {
        $exists_purchases = Purchase::with('ingredient')
            ->where('year', $list['year'])
            ->where('week', $list['week'])
            ->get()
            ->keyBy(
                function ($item) {
                    return $item->ingredient_id.'_'.$item->type;
                }
            );
        
        foreach ($list['suppliers'] as $supplier_id => $supplier) {
            foreach ($supplier['categories'] as $category_id => $category) {
                foreach ($category['ingredients'] as $key => $ingredient) {
                    $purchase = $exists_purchases->get($key);
    
                    if ($purchase) {
                        $in_stock = $purchase->in_stock;
                        $purchase_manager = $purchase->purchase_manager;
                    } else {
                        $in_stock = false;
                        $purchase_manager = false;
                    }
    
                    $list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'][$key]['in_stock'] = $in_stock;
                    $list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'][$key]['purchase_manager'] = $purchase_manager;
                    
                    if ($purchase_manager) {
                        if (!isset($list['categories'][$category_id])) {
                            $list['categories'][$category_id] = [
                                'name'        => $category['name'],
                                'position'    => $category['position'],
                                'ingredients' => [],
                            ];
                        }
                        
                        $list['categories'][$category_id]['ingredients'][$key] = $list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'][$key];
                        
                        unset($list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'][$key]);
                    }
                }
            }
        }
        
        return $list;
    }
    
    /**
     * @param int      $year
     * @param int      $week
     * @param int|bool $supplier_id
     * @param bool     $pre_report
     *
     * @return string
     */
    private function _getDownloadFileName($year, $week, $supplier_id = false, $pre_report = false)
    {
        $supplier = false;
        
        if ($supplier_id > 0) {
            $supplier = Supplier::whereId($supplier_id)->first()->name;
        } elseif ($supplier_id !== false) {
            $supplier = trans('labels.purchase_manager_excel_title');
        }
        
        return ($pre_report ? trans('labels.not_final_version').' ' : '').
            str_replace(' ', '_', trans('labels.list_of_purchase')).
            '_'.trans('labels.w_label').$week.'_'.$year.($supplier ? '_'.str_replace(' ', '_', $supplier) : '');
    }
    
    /**
     * @param int|bool $supplier_id
     * @param bool     $pre_report
     *
     * @return string
     */
    private function _getSheetTabName($supplier_id = false, $pre_report = false)
    {
        $name = trans('labels.purchase');
        
        if ($supplier_id > 0) {
            $name .= ' - '.Supplier::whereId($supplier_id)->first()->name;
        } elseif ($supplier_id !== false) {
            $name .= ' - '.trans('labels.purchase_manager_excel_title');
        }
        
        return ($pre_report ? trans('labels.not_final_version').' ' : '').$name;
    }
    
    /**
     * @param bool|int $supplier_id
     *
     * @return string
     */
    private function _getViewName($supplier_id = false)
    {
        if ($supplier_id === false) {
            return 'download_all';
        }
        
        return 'download';
    }
    
    /**
     * @param int|     $year
     * @param int|     $week
     * @param int|bool $supplier_id
     *
     * @return array
     */
    private function _getPurchaseFor($year, $week, $supplier_id = false)
    {
        $list = Purchase::with('ingredient.unit', 'ingredient.sale_unit')
            ->joinIngredient()
            ->joinIngredientUnit()
            ->joinIngredientCategory()
            ->joinIngredientSupplier()
            ->where('year', $year)
            ->where('week', $week);
        
        if ($supplier_id > 0) {
            $list = $list->where('purchases.supplier_id', $supplier_id);
        } elseif ($supplier_id !== false) {
            $list = $list->where('purchases.purchase_manager', true);
        }
        
        return $list
            ->orderBy('suppliers.priority')
            ->orderBy('categories.position')
            ->orderBy('ingredients.name')
            ->get();
    }
}