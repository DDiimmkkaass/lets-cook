<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.08.16
 * Time: 15:13
 */

namespace App\Services;

use App\Models\Basket;
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
     * @return array
     */
    public function generate()
    {
        $list = [
            'year' => Carbon::now()->year,
            'week' => Carbon::now()->weekOfYear,
        ];
        
        $list['suppliers'] = $this->_getSuppliers();
        
        $this->_processIngredients($list);
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
        
        $purchases = Purchase::with('ingredient', 'ingredient.category', 'ingredient.supplier')
            ->joinIngredient()
            ->joinIngredientSupplier()
            ->joinIngredientCategory()
            ->where('year', $list['year'])
            ->where('week', $list['week'])
            ->orderBy('suppliers.priority')
            ->orderBy('categories.position')
            ->get(['purchases.*'])
            ->keyBy('ingredient_id');
        
        foreach ($purchases as $ingredient) {
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
    
                $list['categories'][$_ingredient->category_id]['ingredients'][$ingredient->ingredient_id] = $ingredient;
            } else {
                if (!isset($list['suppliers'][$_ingredient->supplier_id]['categories'][$_ingredient->category_id])) {
                    $list['suppliers'][$_ingredient->supplier_id]['categories'][$_ingredient->category_id] = [
                        'name'        => $_ingredient->category->name,
                        'position'    => $_ingredient->category->position,
                        'ingredients' => [],
                    ];
                }
    
                $list['suppliers'][$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$ingredient->ingredient_id] = $ingredient;
            }
        }
        
        return $list;
    }
    
    /**
     * @param int      $year
     * @param int      $week
     * @param int|bool $supplier_id
     */
    public function download($year, $week, $supplier_id = false)
    {
        $file_name = $this->_getDownloadFileName($year, $week, $supplier_id);
        $tab_name = $this->_getSheetTabName($supplier_id);
        
        $data = $this->_getPurchaseFor($year, $week, $supplier_id);
        
        foreach ($data as $key => $item) {
            $data[$key] = [
                trans('labels.ingredient') => $item['ingredient'],
                ' '                        => ' ',
                trans('labels.count')      => $item['count'],
                trans('labels.unit')       => $item['unit'],
            ];
        }
        
        Excel::create(
            $file_name,
            function ($excel) use ($data, $tab_name) {
                $excel->sheet(
                    $tab_name,
                    function ($sheet) use ($data) {
                        $sheet->fromArray($data);
                    }
                );
            }
        )->download('xls');
    }
    
    /**
     * @return array
     */
    private function _getSuppliers()
    {
        $orders = Order::ofStatus('processed')->forCurrentWeek()->get(['id'])->pluck('id');
        
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
            ->get();
        
        return $ingredients;
    }
    
    /**
     * @param Collection $ingredients
     * @param array      $recipes
     * @param array|null $suppliers
     *
     * @return array
     */
    private function _buildSuppliersTable($ingredients, $recipes, $suppliers = null)
    {
        $suppliers = $suppliers ? $suppliers : [];
        
        foreach ($ingredients as $ingredient) {
            $_ingredient = $ingredient->ingredient;
            
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
            
            if (!isset($suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$ingredient->ingredient_id])) {
                $data = [
                    'ingredient_id' => $ingredient->ingredient_id,
                    'supplier_id'   => $_ingredient->supplier_id,
                    'name'          => $_ingredient->name,
                    'price'         => $_ingredient->price,
                    'unit'          => $_ingredient->unit->name,
                    'count'         => $count,
                ];
                
                $suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$ingredient->ingredient_id] = $data;
            } else {
                $suppliers[$_ingredient->supplier_id]['categories'][$_ingredient->category_id]['ingredients'][$ingredient->ingredient_id]['count'] += $count;
            }
        }
        
        return $suppliers;
    }
    
    /**
     * @param array $suppliers
     * @param array $orders
     */
    private function _addOrderIngredients(&$suppliers, $orders)
    {
        $ingredients = OrderIngredient::with('ingredient', 'ingredient.category', 'ingredient.supplier')
            ->joinIngredient()
            ->joinIngredientSupplier()
            ->joinIngredientCategory()
            ->whereIn('order_id', $orders)
            ->orderBy('suppliers.priority')
            ->orderBy('categories.position')
            ->get();
        
        $suppliers = $this->_buildSuppliersTable($ingredients, [], $suppliers);
    }
    
    /**
     * @param array $list
     *
     * @return array
     */
    private function _processIngredients($list)
    {
        $ingredients = [];
        
        $exists_purchases = Purchase::with('ingredient')
            ->where('year', $list['year'])
            ->where('week', $list['week'])
            ->get()
            ->keyBy('ingredient_id');
        
        foreach ($list['suppliers'] as $supplier_id => $supplier) {
            foreach ($supplier['categories'] as $category_id => $category) {
                foreach ($category['ingredients'] as $ingredient_id => $ingredient) {
                    if (!$exists_purchases->has($ingredient_id)) {
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
                        $purchase = $exists_purchases->get($ingredient_id);
                        
                        $purchase->fill($ingredient);
                        
                        if ($purchase->isDirty()) {
                            $purchase->save();
                        }
                    }
                    
                    $ingredients[] = $ingredient_id;
                }
            }
        }
        
        Purchase::where('week', $list['week'])->where('year', $list['year'])
            ->whereNotIn('ingredient_id', $ingredients)
            ->delete();
    }
    
    /**
     * @param int      $year
     * @param int      $week
     * @param int|bool $supplier
     *
     * @return string
     */
    private function _getDownloadFileName($year, $week, $supplier = false)
    {
        if ($supplier > 0) {
            $supplier = Supplier::whereId($supplier)->first()->name;
        } elseif ($supplier !== false) {
            $supplier = trans('labels.purchase_manager_excel_title');
        }
        
        return str_replace(' ', '_', trans('labels.list_of_purchase')).
        '_'.trans('labels.w_label').$week.'_'.$year.($supplier ? '_'.str_replace(' ', '_', $supplier) : '');
    }
    
    /**
     * @param int|bool $supplier
     *
     * @return string
     */
    private function _getSheetTabName($supplier = false)
    {
        if ($supplier > 0) {
            $name = Supplier::whereId($supplier)->first()->name;
        } elseif ($supplier !== false) {
            $name = trans('labels.purchase_manager_excel_title');
        }
        
        return $name;
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
        $list = Purchase::joinIngredient()->joinIngredientUnit()->where('year', $year)->where('week', $week);
        
        if ($supplier_id > 0) {
            $list = $list->where('purchases.supplier_id', $supplier_id);
        } elseif ($supplier_id !== false) {
            $list = $list->where('purchases.purchase_manager', true);
        }
        
        $list = $list->select(
            DB::raw('ingredients.name as ingredient'),
            'purchases.count',
            DB::raw('units.name as unit')
        )
            ->get()
            ->toArray();
        
        return $list;
    }
}