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
use App\Models\OrderBasket;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\Purchase;
use App\Models\RecipeIngredient;
use App\Models\Supplier;
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
            'year' => active_week()->year,
            'week' => active_week()->weekOfYear,
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
            ->orderBy('suppliers.name')
            ->orderBy('categories.position')
            ->orderBy('categories.name')
            ->orderBy('ingredients.name')
            ->get(['purchases.*'])
            ->keyBy(
                function ($item) {
                    return $item->ingredient_id.'_'.$item->type;
                }
            );
        
        $ordered = $this->_getOrderedIngredientsList($year, $week);
        
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
                
                $ingredient->count = isset($ordered[$key]) ? $ordered[$key]['count'] : $ingredient->count;
                
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
     * @param bool     $download
     *
     * @return string
     */
    public function download($year, $week, $supplier_id = false, $pre_report = false, $download = true)
    {
        $supplier_name = $supplier_id !== false ?
            ($supplier_id == 0 ?
                trans('labels.purchase_manager_excel_title') :
                Supplier::whereId($supplier_id)->first()->name
            ) : '';
        
        $file_name = $this->_getDownloadFileName($year, $week, $supplier_name, $pre_report, $download);
        $sheet_name = $this->_getSheetTabName($supplier_name, $pre_report);
        $view = $this->_getViewName($pre_report);
        
        $baskets = $this->_basketsForReport($year, $week, $pre_report);
        
        $ordered = $this->_getOrderedIngredientsList($year, $week, $supplier_id);
        
        if ($pre_report) {
            $list = $ordered;
        } else {
            $list = $this->_getPurchaseFor($year, $week, $supplier_id);
        }
        
        $data = [
            'year'          => $year,
            'week'          => $week,
            'list'          => $list,
            'ordered'       => $ordered,
            'supplier_name' => $supplier_name,
            'pre_report'    => $pre_report,
            'baskets'       => $baskets,
            'sheet_name'    => $sheet_name,
            'view'          => $view,
        ];
        
        $excel = Excel::create(
            $file_name,
            function ($excel) use ($data) {
                $excel->sheet(
                    get_excel_sheet_name($data['sheet_name']),
                    function ($sheet) use ($data) {
                        $sheet->loadView('views.purchase.partials.'.$data['view'])
                            ->with($data)
                            ->setOrientation('landscape')
                            ->getStyle('A1:Z'.$sheet->getHighestRow())
                            ->getAlignment()->setWrapText(true);
                    }
                );
            }
        );
        
        $excel->store('xls', false, true);
        
        return $download ?
            $excel->download() :
            config('excel.export.store.path').'/'.$excel->getFileName().'.'.$excel->ext;
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
        
        $_baskets = OrderBasket::additional()
            ->with('basket', 'basket.recipes')
            ->whereIn('order_baskets.order_id', $orders)
            ->select('order_baskets.basket_id', DB::raw('count(order_baskets.basket_id) as baskets_count'))
            ->groupBy('order_baskets.basket_id')
            ->get();
        
        foreach ($_baskets as $basket) {
            foreach ($basket->basket->recipes as $recipe) {
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
            ->orderBy('suppliers.name')
            ->orderBy('categories.position')
            ->orderBy('categories.name')
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
            ->orderBy('suppliers.name')
            ->orderBy('categories.position')
            ->orderBy('categories.name')
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
        
        if (empty($ingredients)) {
            Purchase::where('week', $list['week'])->where('year', $list['year'])->delete();
        } else {
            foreach ($ingredients as $type => $_ingredients) {
                Purchase::where('week', $list['week'])->where('year', $list['year'])
                    ->whereType($type)
                    ->whereNotIn('ingredient_id', $_ingredients)
                    ->delete();
            }
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
                        $list['categories'][$category_id]['ingredients'][$key]['supplier_name'] = $supplier['name'];
                        
                        unset($list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'][$key]);
                    }
                }
                
                if (empty($list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'])) {
                    unset($list['suppliers'][$supplier_id]['categories'][$category_id]);
                }
            }
            
            if (empty($list['suppliers'][$supplier_id]['categories'])) {
                unset($list['suppliers'][$supplier_id]);
            }
        }
        
        return $list;
    }
    
    /**
     * @param int    $year
     * @param int    $week
     * @param string $supplier_name
     * @param bool   $pre_report
     * @param bool   $download
     *
     * @return string
     */
    private function _getDownloadFileName($year, $week, $supplier_name, $pre_report = false, $download = true)
    {
        $file_name = $pre_report ? trans('labels.not_final_version').'. ' : '';
        
        $file_name .= str_replace(' ', '_', trans('labels.list_of_purchase')).'_'.
            trans('labels.w_label').$week.'_'.$year;
        
        $file_name .= $supplier_name ? '_'.$supplier_name : '';
    
        $file_name = str_replace(' ', '_', $file_name);
        
        return $download ? $file_name : trans('labels.all_prefix').' '.$file_name;
    }
    
    /**
     * @param string $supplier_name
     * @param bool   $pre_report
     *
     * @return string
     */
    private function _getSheetTabName($supplier_name, $pre_report = false)
    {
        $name = trans('labels.purchase').($supplier_name ? ' - '.$supplier_name : '');
        
        return ($pre_report ? trans('labels.not_final_version').' ' : '').$name;
    }
    
    /**
     * @param bool $pre_report
     *
     * @return string
     */
    private function _getViewName($pre_report = false)
    {
        return 'download'.($pre_report ? '_pre_report' : '');
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
            ->orderBy('suppliers.name')
            ->orderBy('categories.position')
            ->orderBy('categories.name')
            ->orderBy('ingredients.name')
            ->get(['purchases.*']);
    }
    
    /**
     * @param array    $list
     * @param int|bool $supplier_id
     *
     * @return array
     */
    private function _prepareToDownload($list, $supplier_id = false)
    {
        $_list = [];
        
        if ($supplier_id > 0) {
            foreach ($list['suppliers'][$supplier_id]['categories'] as $category_id => $category) {
                foreach ($category['ingredients'] as $key => $ingredient) {
                    $_list[$key] = $ingredient;
                    $_list[$key]['supplier_name'] = $list['suppliers'][$supplier_id]['name'];
                    $_list[$key]['category_name'] = $category['name'];
                    
                    unset($list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'][$key]);
                }
                
                unset($list['suppliers'][$supplier_id]['categories'][$category_id]);
            }
            
            return $_list;
        } elseif ($supplier_id !== false) {
            foreach ($list['categories'] as $category_id => $category) {
                foreach ($category['ingredients'] as $key => $ingredient) {
                    $_list[$key] = $ingredient;
                    $_list[$key]['category_name'] = $category['name'];
                    
                    unset($list['categories'][$category_id]['ingredients'][$key]);
                }
                
                unset($list['categories'][$category_id]);
            }
            
            return $_list;
        }
        
        foreach ($list['suppliers'] as $supplier_id => $supplier) {
            foreach ($supplier['categories'] as $category_id => $category) {
                foreach ($category['ingredients'] as $key => $ingredient) {
                    $_list[$key] = $ingredient;
                    $_list[$key]['supplier_name'] = $supplier['name'];
                    $_list[$key]['category_name'] = $category['name'];
                    
                    unset($list['suppliers'][$supplier_id]['categories'][$category_id]['ingredients'][$key]);
                }
                
                unset($list['suppliers'][$supplier_id]['categories'][$category_id]);
            }
            
            unset($list['suppliers'][$supplier_id]);
        }
        
        return $_list;
    }
    
    /**
     * @param int  $year
     * @param int  $week
     * @param bool $pre_report
     *
     * @return array
     */
    private function _basketsForReport($year, $week, $pre_report = false)
    {
        $baskets = [];
        
        $orders = $this->_getOrders($year, $week, $pre_report);
        
        $_order_baskets = OrderBasket::with('weekly_menu_basket', 'weekly_menu_basket.recipes')
            ->main()
            ->whereIn('order_id', $orders)
            ->get();
        foreach ($_order_baskets as $key => $basket) {
            if (!isset($baskets[$basket->weekly_menu_basket->basket_id])) {
                $baskets[$basket->weekly_menu_basket->basket_id] = [
                    'name'        => $basket->weekly_menu_basket->getCode(),
                    'count'       => 0,
                    'ingredients' => [],
                ];
            }
            
            foreach ($basket->weekly_menu_basket->recipes->sortBy('position') as $recipe) {
                foreach ($recipe->recipe->ingredients as $ingredient) {
                    if (!isset($baskets[$basket->weekly_menu_basket->basket_id]['ingredients'][$ingredient->ingredient_id])) {
                        $baskets[$basket->weekly_menu_basket->basket_id]['ingredients'][$ingredient->ingredient_id] = $ingredient->count;
                    }
                }
            }
            
            $baskets[$basket->weekly_menu_basket->basket_id]['count']++;
            
            unset($_order_baskets[$key]);
        }
        
        $additional_baskets = Basket::with('recipes')->additional()->get();
        foreach ($additional_baskets as $key => $basket) {
            $recipe = $basket->recipes->sortBy('position')->first();
            
            if ($recipe) {
                $count = $this->_getAdditionalBasketOrdersCount($basket->id, $year, $week, $pre_report);
                
                $baskets[$basket->id] = [
                    'name'  => $recipe->getName(),
                    'count' => $count,
                ];
                
                foreach ($recipe->recipe->ingredients as $ingredient) {
                    if (!isset($baskets[$basket->id]['ingredients'][$ingredient->ingredient_id])) {
                        $baskets[$basket->id]['ingredients'][$ingredient->ingredient_id] = $ingredient->count;
                    }
                }
            }
            
            unset($_order_baskets[$key]);
        }
        
        return $baskets;
    }
    
    /**
     * @param int  $basket_id
     * @param int  $year
     * @param int  $week
     * @param bool $pre_generation
     *
     * @return int
     */
    private function _getAdditionalBasketOrdersCount($basket_id, $year, $week, $pre_generation = false)
    {
        $orders = $this->_getOrders($year, $week, $pre_generation);
        
        $count = Order::joinOrderBaskets()
            ->whereIn('orders.id', $orders)
            ->where('order_baskets.basket_id', $basket_id)
            ->count();
        
        return $count;
    }
    
    /**
     * @param      $year
     * @param      $week
     * @param bool $pre_generation
     *
     * @return Collection
     */
    private function _getOrders($year, $week, $pre_generation = false)
    {
        if ($pre_generation) {
            $orders = Order::notOfStatus(['archived', 'deleted']);
        } else {
            $orders = Order::ofStatus('processed');
        }
        
        return $orders->forWeek($year, $week)->get(['id'])->pluck('id');
    }
    
    /**
     * @param int  $year
     * @param int  $week
     * @param bool $supplier_id
     *
     * @return array
     */
    private function _getOrderedIngredientsList($year, $week, $supplier_id = false)
    {
        $list = $this->preGenerate($year, $week);
        
        $list = $this->_prepareToDownload($list, $supplier_id);
        
        return $list;
    }
}