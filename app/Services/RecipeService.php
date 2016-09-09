<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.06.16
 * Time: 10:40
 */

namespace App\Services;

use App\Models\BasketRecipe;
use App\Models\Order;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;
use App\Transformers\RecipeTransformer;
use Carbon;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class RecipeService
 * @package App\Services
 */
class RecipeService
{
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json
     */
    public function table(Request $request)
    {
        $last_uses_filter = $this->_getLastUsesFilter($request);
        
        $list = Recipe::with('baskets', 'tags')
            ->joinBaskets()
            ->select(
                'recipes.id',
                'recipes.name',
                'recipes.image',
                DB::raw('1 as baskets_list'),
                'recipes.portions',
                DB::raw('2 as recipe_tags'),
                DB::raw(
                    '(SELECT SUM(_i.price * _ri.count) / 100
                        FROM recipe_ingredients _ri
                            LEFT JOIN ingredients _i ON (_ri.ingredient_id = _i.id)
                        WHERE _ri.recipe_id = recipes.id 
                            AND _ri.type = '.RecipeIngredient::getTypeIdByName('normal').'
                    ) as recipe_price'
                ),
                DB::raw(
                    '(SELECT _wm.id
                        FROM basket_recipes _br
                          INNER JOIN weekly_menu_baskets _wmb ON (_wmb.id = _br.weekly_menu_basket_id)
                          INNER JOIN weekly_menus _wm ON (_wm.id = _wmb.weekly_menu_id)
                        WHERE _br.weekly_menu_basket_id IS NOT NULL AND
                              _br.recipe_id = recipes.id '.
                    $last_uses_filter['filter_from'].$last_uses_filter['filter_to'].
                    ' ORDER BY _wm.year DESC ,_wm.week DESC 
                         LIMIT 1 
                     ) as last_uses'
                ),
                'recipes.status',
                'recipes.draft'
            )
            ->groupBy('recipes.id');
        
        $this->_implodeFilters($list, $request);
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'recipes.id', '=', '$1')
            ->filterColumn('name', 'where', 'recipes.name', 'LIKE', '%$1%')
            ->editColumn(
                'name',
                function ($model) {
                    $html = $model->name;
                    
                    if ($model->image) {
                        $html = view(
                                'partials.image',
                                [
                                    'src'        => $model->image,
                                    'attributes' => ['width' => 50, 'class' => 'margin-right-10'],
                                ]
                            )->render().$html;
                    }
                    
                    if ($model->draft) {
                        $html .= view('recipe.partials.draft_label')->render();
                    }
                    
                    return $html;
                }
            )
            ->editColumn(
                'baskets_list',
                function ($model) {
                    return $model->baskets->implode('name', '<br>');
                }
            )
            ->editColumn(
                'recipe_tags',
                function ($model) {
                    $tags = '';
                    
                    foreach ($model->tags as $tag) {
                        $tags .= $tag->tag->name.', ';
                    }
                    
                    return trim($tags, ', ');
                }
            )
            ->editColumn(
                'recipe_price',
                function ($model) {
                    return (int) $model->recipe_price.' '.currency();
                }
            )
            ->editColumn(
                'last_uses',
                function ($model) {
                    if (!empty($model->last_uses)) {
                        $menu = WeeklyMenu::whereId($model->last_uses)->first();
                        
                        return link_to_route('admin.weekly_menu.show', $menu->getName(), $model->last_uses)->toHtml();
                    }
                    
                    return '';
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view(
                        'partials.datatables.toggler',
                        ['model' => $model, 'type' => 'recipe', 'field' => 'status']
                    )->render();
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view('recipe.datatables.control_buttons', ['model' => $model])->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('ingredients')
            ->removeColumn('baskets')
            ->removeColumn('tags')
            ->removeColumn('image')
            ->removeColumn('draft')
            ->make();
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json
     */
    public function tableFind(Request $request)
    {
        $last_uses_filter = $this->_getLastUsesFilter($request);
        
        $list = Recipe::with('tags')
            ->joinBaskets()
            ->select(
                'recipes.id',
                'recipes.name',
                'recipes.image',
                DB::raw('2 as recipe_tags'),
                DB::raw(
                    '(SELECT SUM(_i.price * _ri.count) / 100
                            FROM recipe_ingredients _ri
                            LEFT JOIN ingredients _i ON (_ri.ingredient_id = _i.id)
                            WHERE _ri.recipe_id = recipes.id 
                            AND _ri.type = '.RecipeIngredient::getTypeIdByName('normal').') as recipe_price'
                ),
                DB::raw(
                    '(SELECT _wm.id
                        FROM basket_recipes _br
                          INNER JOIN weekly_menu_baskets _wmb ON (_wmb.id = _br.weekly_menu_basket_id)
                          INNER JOIN weekly_menus _wm ON (_wm.id = _wmb.weekly_menu_id)
                        WHERE _br.weekly_menu_basket_id IS NOT NULL AND
                              _br.recipe_id = recipes.id '.
                    $last_uses_filter['filter_from'].$last_uses_filter['filter_to'].
                    ' ORDER BY _wm.year DESC ,_wm.week DESC 
                         LIMIT 1 
                     ) as last_uses'
                ),
                'recipes.status',
                'recipes.draft'
            )
            ->visible()
            ->groupBy('recipes.id');
        
        $this->_implodeFilters($list, $request);
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'recipes.id', '=', '$1')
            ->filterColumn('name', 'where', 'recipes.name', 'LIKE', '%$1%')
            ->editColumn(
                'name',
                function ($model) {
                    $html = $model->name;
                    
                    if ($model->image) {
                        $html = view(
                                'partials.image',
                                [
                                    'src'        => $model->image,
                                    'attributes' => ['width' => 50, 'class' => 'margin-right-10'],
                                ]
                            )->render().$html;
                    }
                    
                    if ($model->draft) {
                        $html .= view('recipe.partials.draft_label')->render();
                    }
                    
                    return $html;
                }
            )
            ->editColumn(
                'recipe_tags',
                function ($model) {
                    $tags = '';
                    
                    foreach ($model->tags as $tag) {
                        $tags .= $tag->tag->name.', ';
                    }
                    
                    return trim($tags, ', ');
                }
            )
            ->editColumn(
                'recipe_price',
                function ($model) {
                    return (int) $model->recipe_price.' '.currency();
                }
            )
            ->editColumn(
                'last_uses',
                function ($model) {
                    if (!empty($model->last_uses)) {
                        $menu = WeeklyMenu::whereId($model->last_uses)->first();
                        
                        return link_to_route('admin.weekly_menu.show', $menu->getName(), $model->last_uses)->toHtml();
                    }
                    
                    return '';
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view('recipe.datatables.control_buttons_find', ['model' => $model])->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('baskets')
            ->removeColumn('tags')
            ->removeColumn('image')
            ->removeColumn('status')
            ->removeColumn('draft')
            ->make();
    }
    
    /**
     * @param \App\Models\Recipe $parent_model
     * @param \App\Models\Recipe $model
     */
    public function bindRecipes(Recipe $parent_model, Recipe $model)
    {
        $bind_id = $parent_model->bind_id ? $parent_model->bind_id : md5($parent_model->id);
        
        $parent_model->bind_id = $model->bind_id = $bind_id;
        
        $parent_model->save();
        $model->save();
    }
    
    /**
     * @param \App\Models\Recipe $model
     * @param array              $ingredients
     */
    public function processIngredients(Recipe $model, $ingredients = [])
    {
        $data = isset($ingredients['remove']) ? $ingredients['remove'] : [];
        foreach ($data as $id) {
            try {
                $ingredient = RecipeIngredient::findOrFail($id);
                $ingredient->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.ingredient destroy failure"." ".$id));
            }
        }
        
        $data = isset($ingredients['old']) ? $ingredients['old'] : [];
        foreach ($data as $id => $ingredient) {
            try {
                $_ingredient = RecipeIngredient::findOrFail($id);
                
                $_ingredient->fill($ingredient);
                
                $_ingredient->save();
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.ingredient update failure"." ".$id)
                );
            }
        }
        
        $data = isset($ingredients['new']) ? $ingredients['new'] : [];
        foreach ($data as $id => $ingredient) {
            try {
                $ingredient['type'] = isset($ingredient['type']) ? $ingredient['type'] : 0;
                $ingredient = new RecipeIngredient($ingredient);
                
                $model->ingredients()->save($ingredient);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.ingredient save failure"." ".$ingredient['name'])
                );
            }
        }
    }
    
    /**
     * @param \App\Models\Recipe $model
     * @param array              $steps
     */
    public function processSteps($model, $steps = [])
    {
        $data = isset($steps['remove']) ? $steps['remove'] : [];
        foreach ($data as $id) {
            try {
                $step = $model->steps()->findOrFail($id);
                $step->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.step destroy failure"." ".$id));
            }
        }
        
        $data = isset($steps['old']) ? $steps['old'] : [];
        foreach ($data as $key => $step) {
            try {
                $_step = RecipeStep::findOrFail($key);
                $_step->update($step);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.step update failure"." ".$step['name'])
                );
            }
        }
        
        $data = isset($steps['new']) ? $steps['new'] : [];
        foreach ($data as $step) {
            try {
                $step = new RecipeStep($step);
                $model->steps()->save($step);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.step save failure"." ".$step['name'])
                );
            }
        }
    }
    
    /**
     * @param int   $recipe_id
     * @param array $select
     *
     * @return mixed
     */
    public function getWeeklyMenusWhereUsedRecipe($recipe_id, $select = [])
    {
        return WeeklyMenu::joinWeeklyMenuBaskets()
            ->joinBasketRecipes()
            ->whereNotNull('basket_recipes.weekly_menu_basket_id')
            ->where('week', '>=', Carbon::now()->weekOfYear)
            ->where('basket_recipes.recipe_id', $recipe_id)
            ->groupBy('weekly_menus.id')
            ->get(empty($select) ? ['weekly_menus.*'] : $select);
    }
    
    /**
     * @param \App\Models\Recipe $recipe
     *
     * @return array
     */
    public function getStatisticOfOrder(Recipe $recipe)
    {
        $statistic = [];
        
        $order_recipes = $recipe->order_recipes()
            ->joinOrder()
            ->where('orders.status', Order::getStatusIdByName('archived'))
            ->get();
        
        foreach ($order_recipes as $order_item) {
            $week = trans('labels.w_label').$order_item->created_at->weekOfYear.', '.$order_item->created_at->year;
            
            if (!isset($statistic[$week])) {
                $statistic[$week] = 0;
            }
            
            $statistic[$week] += 1;
        }
        
        $additional_baskets = BasketRecipe::whereRecipeId($recipe->id)->whereNotNull('basket_id')
            ->groupBy('basket_id')
            ->get(['basket_id'])
            ->pluck('basket_id')
            ->toArray();
        
        $orders = Order::joinAdditionalBaskets()
            ->ofStatus('archived')
            ->whereIn('basket_id', $additional_baskets)
            ->get();
        
        foreach ($orders as $order) {
            $week = trans('labels.w_label').$order->created_at->weekOfYear.', '.$order->created_at->year;
            
            if (!isset($statistic[$week])) {
                $statistic[$week] = 0;
            }
            
            $statistic[$week] += 1;
        }
        
        return $statistic;
    }
    
    /**
     * @param \App\Models\Recipe $recipe
     *
     * @return array
     */
    public function getStatisticOfUses(Recipe $recipe)
    {
        $statistic = [];
    
        $menus = WeeklyMenu::joinWeeklyMenuBaskets()
            ->joinBasketRecipes()
            ->whereNotNull('basket_recipes.weekly_menu_basket_id')
            ->where('basket_recipes.recipe_id', $recipe->id)
            ->groupBy('weekly_menus.id')
            ->orderBy('weekly_menus.year', 'DESC')->orderBy('weekly_menus.week', 'DESC')
            ->get(empty($select) ? ['weekly_menus.*'] : $select);
        
        foreach ($menus as $menu) {
            $statistic[$menu->id] = $menu->getName();
        }
        
        return $statistic;
    }
    
    /**
     * @param int  $recipe_id
     * @param bool $copy
     * @param int  $portions
     *
     * @return null|Recipe
     */
    public function getRecipeFormWeeklyMenu($recipe_id, $copy, $portions)
    {
        if ($copy) {
            $recipe = Recipe::visible()
                ->whereBindId(DB::raw('(SELECT bind_id FROM recipes WHERE id = \''.$recipe_id.'\')'))
                ->wherePortions($portions)
                ->first();
            
            return $recipe;
        }
        
        $recipe = Recipe::visible()->findOrFail($recipe_id);
        
        return $recipe;
    }
    
    /**
     * @return LengthAwarePaginator
     */
    public function getList()
    {
        $list = Recipe::with(['tags', 'tags.tag.translations', 'ingredients'])->visible()->nameSorted();
        
        $list = $this->_implodeFrontFilters($list);
        
        if (request('page', 1) == 0) {
            $list = $list->get();
        } else {
            $list = $this->_paginate($list);
        }
        
        $list = $this->_prepareData($list);
        
        return $list;
    }
    
    /**
     * @param \App\Models\Recipe $model
     *
     * @return Collection
     */
    public function activeBaskets(Recipe $model)
    {
        $now = Carbon::now()->startOfWeek();
        
        return WeeklyMenuBasket::with('basket')
            ->joinWeeklyMenu()
            ->joinBasket()
            ->joinBasketRecipes()
            ->where('weekly_menus.year', $now->year)
            ->where('weekly_menus.week', $now->weekOfYear)
            ->where('basket_recipes.recipe_id', $model->id)
            ->whereNotNull('basket_recipes.weekly_menu_basket_id')
            ->groupBy('weekly_menu_baskets.basket_id')
            ->get(['weekly_menu_baskets.id', 'baskets.name']);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    private function _getLastUsesFilter(Request $request)
    {
        $filters = $request->get('datatable_filters', []);
        
        $filter_from = isset($filters['last_uses_from']) ? $filters['last_uses_from'] : '';
        if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $filter_from)) {
            $value = Carbon::createFromFormat('d-m-Y', $filter_from);
            
            $filter_from = ' AND _wm.week >= \''.$value->weekOfYear.'\' '.
                ' AND _wm.year >= \''.$value->year.'\' ';
        }
        
        $filter_to = isset($filters['last_uses_to']) ? $filters['last_uses_to'] : '';
        if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $filter_to)) {
            $value = Carbon::createFromFormat('d-m-Y', $filter_to);
            
            $filter_to = ' AND _wm.week <= \''.$value->weekOfYear.'\' '.
                ' AND _wm.year <= \''.$value->year.'\' ';
        }
        
        return [
            'filter_from' => $filter_from,
            'filter_to'   => $filter_to,
        ];
    }
    
    /**
     * @param Builder $list
     * @param Request $request
     */
    private function _implodeFilters(&$list, $request)
    {
        $filters = $request->get('datatable_filters');
        
        if (count($filters)) {
            foreach ($filters as $filter => $value) {
                if ($value !== '' && $value !== 'null') {
                    switch ($filter) {
                        case 'name':
                            $list->where('recipes.name', 'LIKE', '%'.$value.'%');
                            break;
                        case 'basket':
                            $list->where('basket_recipe.basket_id', $value);
                            break;
                        case 'portions':
                            $list->where('recipes.portions', $value);
                            break;
                        case 'price_from':
                            $list->having('recipe_price', '>=', $value);
                            break;
                        case 'price_to':
                            $list->having('recipe_price', '<=', $value);
                            break;
                        case 'last_uses_from':
                            if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $value)) {
                                $list->havingRaw('last_uses IS NOT NULL');
                            }
                            break;
                        case 'last_uses_to':
                            if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $value)) {
                                $list->havingRaw('last_uses IS NOT NULL');
                            }
                            break;
                        case 'tags':
                            $list->joinTags()->whereIn('tags.id', explode(',', $value));
                            break;
                        case 'status':
                            $list->where('recipes.status', $value);
                            break;
                    }
                }
            }
        }
    }
    
    /**
     * @param Builder $list
     *
     * @return Builder
     */
    private function _implodeFrontFilters(Builder $list)
    {
        $category_id = request()->route('category_id', 0);
        $tag_id = request()->route('tag_id', 0);
        $search_text = request('search_text', '');
        
        if ($category_id > 0) {
            $list->whereExists(
                function ($query) use ($category_id, $tag_id) {
                    $query->leftJoin('tags', 'tags.id', '=', 'tagged.tag_id')
                        ->select(DB::raw('1'))
                        ->from('tagged')
                        ->whereRaw(
                            'recipes.id = tagged.taggable_id AND
                            tagged.taggable_type = \''.str_replace('\\', '\\\\', Recipe::class).'\' AND
                            tags.category_id = '.$category_id.
                            ($tag_id > 0 ? ' AND tagged.tag_id = '.$tag_id : '')
                        );
                }
            );
        }
        
        if (!$category_id && $tag_id > 0) {
            $list->whereExists(
                function ($query) use ($tag_id) {
                    $query->select(DB::raw('1'))
                        ->from('tagged')
                        ->whereRaw(
                            'recipes.id = tagged.taggable_id AND
                            tagged.taggable_type = \''.str_replace('\\', '\\\\', Recipe::class).'\' AND
                            tagged.tag_id = '.$tag_id
                        );
                }
            );
        }
        
        if (!empty($search_text)) {
            $list->search($search_text);
        }
        
        return $list;
    }
    
    /**
     * @param Builder $list
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function _paginate($list)
    {
        $page = request('page', 1);
    
        $total = (clone $list);
        $list = $list->take(config('recipe.per_page'))->skip(($page - 1) * config('recipe.per_page'))->get();
    
        return new LengthAwarePaginator(
            $list,
            $total->get()->count(),
            config('recipe.per_page'),
            $page,
            [
                'path'  => route('recipes.index'),
                'query' => ['search_text' => request('search_text', '')],
            ]
        );
    }
    
    /**
     * @param $list
     *
     * @return array
     */
    private function _prepareData($list)
    {
        $data = ['recipes' => []];
        
        foreach ($list as $item) {
            $data['recipes'][] = RecipeTransformer::transform($item);
        }
        
        if ($list instanceof Collection) {
            $data['next_count'] = 0;
        } else {
            if ($list->lastPage() == $list->currentPage()) {
                $data['next_count'] = 0;
            } else {
                $data['next_count'] = $list->total() - $list->currentPage() * $list->perPage();
                $data['next_count'] = $data['next_count'] > $list->perPage() ? $list->perPage() : $data['next_count'];
                
                $data['next_count'] = $data['next_count'] >= 0 ? $data['next_count'] : 0;
            }
        }
    
        $data['next_count_label'] = trans('front_labels.pagination_next').' '.
            $data['next_count'].' '.
            trans_choice('front_labels.count_of_recipes', $data['next_count']);
        
        return $data;
    }
}