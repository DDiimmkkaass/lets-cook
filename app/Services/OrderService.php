<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 15.07.16
 * Time: 10:36
 */

namespace App\Services;

use App\Models\Basket;
use App\Models\BasketRecipe;
use App\Models\Order;
use App\Models\OrderComment;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\User;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;
use Carbon;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Sentry;

/**
 * Class OrderService
 * @package App\Services
 */
class OrderService
{
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json
     */
    public function table(Request $request)
    {
        $list = Order::select(
            'id',
            'full_name',
            'user_id',
            'type',
            'subscribe_period',
            'payment_method',
            'status',
            'created_at',
            'delivery_date',
            'delivery_time',
            'total'
        );
        
        $this->_implodeFilters($list, $request);
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'orders.id', '=', '$1')
            ->filterColumn('full_name', 'where', 'orders.full_name', 'LIKE', '%$1%')
            ->editColumn(
                'full_name',
                function ($model) {
                    return '<a href="'.route('admin.user.show', $model->user_id).'" 
                            title="'.trans('labels.go_to_user').' '.$model->getUserFullName().'">
                            '.$model->getUserFullName().'
                            </a>';
                }
            )
            ->editColumn(
                'type',
                function ($model) {
                    $html = trans('labels.order_type_'.$model->getStringType());
                    $html .= $model->isSubscribe() ?
                        ' ('.trans_choice('labels.subscribe_period_label', $model->subscribe_period).')' :
                        '';
                    
                    return $html;
                }
            )
            ->editColumn(
                'payment_method',
                function ($model) {
                    return trans('labels.payment_method_'.$model->getStringPaymentMethod());
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view(
                        'partials.datatables.status_label',
                        [
                            'status' => $model->getStringStatus(),
                            'label'  => trans('labels.order_status_'.$model->getStringStatus()),
                        ]
                    )->render();
                }
            )
            ->editColumn(
                'created_at',
                function ($model) {
                    return view(
                        'partials.datatables.humanized_date',
                        [
                            'date'        => $model->created_at,
                            'time_format' => 'H:i',
                        ]
                    )->render();
                }
            )
            ->editColumn(
                'delivery_date',
                function ($model) {
                    $html = view(
                        'partials.datatables.humanized_date',
                        [
                            'date'      => $model->delivery_date,
                            'in_format' => 'd-m-Y',
                        ]
                    )->render();
                    
                    return '<div class="text-center">'.$model->delivery_time.' '.$html.'<div>';
                }
            )
            ->editColumn(
                'total',
                function ($model) {
                    return $model->total.' '.currency();
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view(
                        'partials.datatables.control_buttons',
                        [
                            'model'          => $model,
                            'type'           => 'order',
                            'without_delete' => true,
                        ]
                    )->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('user_id')
            ->removeColumn('subscribe_period')
            ->removeColumn('delivery_time')
            ->make();
    }
    
    /**
     * @return array
     */
    public function getOrdersStatistic()
    {
        $statistic = [
            'days'               => [],
            'baskets'            => [],
            'additional_baskets' => [],
            'count'              => 0,
            'sum'                => 0,
            'sum_with_discount'  => 0,
        ];
        
        $orders = Order::notOfStatus(['archived', 'deleted'])->forCurrentWeek()->orderBy('delivery_date')->get();
        
        foreach ($orders as $order) {
            if (!isset($statistic['days'][$order->delivery_date])) {
                $statistic['days'][$order->delivery_date] = [
                    'day'               => $order->delivery_date,
                    'title'             => day_of_week($order->delivery_date, 'd-m-Y'),
                    'count'             => 0,
                    'sum'               => 0,
                    'sum_with_discount' => 0,
                ];
            }
            
            $statistic['days'][$order->delivery_date]['count']++;
            $statistic['days'][$order->delivery_date]['sum'] += $order->total;
            $statistic['days'][$order->delivery_date]['sum_with_discount'] += $order->total;
            
            $statistic['count']++;
            $statistic['sum'] += $order->total;
            $statistic['sum_with_discount'] += $order->total;
        }
        
        $recipes = OrderRecipe::with('recipe', 'recipe.recipe', 'recipe.weekly_menu_basket', 'order')
            ->whereIn('order_id', $orders->pluck('id'))
            ->get();
        
        foreach ($recipes as $recipe) {
            if (!isset($statistic['baskets'][$recipe->recipe->weekly_menu_basket_id])) {
                $statistic['baskets'][$recipe->recipe->weekly_menu_basket_id] = [
                    'name'    => $recipe->recipe->weekly_menu_basket->basket->name,
                    'recipes' => [],
                ];
            }
            
            if (!isset($statistic['baskets'][$recipe->recipe->weekly_menu_basket_id]['recipes'][$recipe->recipe->id])) {
                $statistic['baskets'][$recipe->recipe->weekly_menu_basket_id]['recipes'][$recipe->recipe->id] = [
                    'recipe' => $recipe->recipe,
                    'count'  => 0,
                ];
            }
            
            $statistic['baskets'][$recipe->recipe->weekly_menu_basket_id]['recipes'][$recipe->recipe->id]['count']++;
        }
        
        $statistic['additional_baskets'] = Basket::additional()->joinBasketOrder()->joinOrders()
            ->with('recipes')
            ->whereIn('orders.id', $orders->pluck('id'))
            ->select(
                'baskets.id',
                'baskets.name',
                DB::raw('SUM(baskets.price) as total'),
                DB::raw('count(basket_id) as count')
            )
            ->groupBy('basket_order.basket_id')
            ->get();
        
        return $statistic;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function prepareInputData(Request $request)
    {
        $data = $request->all();
        
        $data['city_id'] = empty($data['city']) ? $data['city_id'] : null;
        
        return $data;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param User|null                $user
     *
     * @return array
     */
    public function prepareFrontInputData(Request $request, $user = null)
    {
        $data = [];
        
        $data['user_id'] = $user ? $user->id : null;
        $data['full_name'] = $user ? $user->full_name : $request->get('full_name');
        $data['email'] = $user ? $user->email : $request->get('email');
        $data['phone'] = $user ? $user->phone : $request->get('phone');
        
        $data['basket_id'] = $request->get('basket_id');
        
        $data['type'] = empty($input['subscribe_period']) ?
            Order::getTypeIdByName('subscribe') :
            Order::getTypeIdByName('single');
        
        $data['subscribe_period'] = $request->get('subscribe_period', 0);
        $data['payment_method'] = $request->get('payment_method');
        
        $data['delivery_date'] = $request->get('delivery_date');
        $data['delivery_time'] = $request->get('delivery_time');
        
        $data['city_id'] = $request->get('city_id');
        $data['city_name'] = empty($data['city_id']) ? $request->get('city_name') : '';
        $data['address'] = $request->get('address');
        $data['comment'] = $request->get('comment');
        
        $data['verify_call'] = empty($request->get('verify_call', null)) ? 0 : 1;
        
        $data['status'] = Order::getStatusIdByName('changed');
        
        return $data;
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $input
     */
    public function saveRelationships(Order $model, $input)
    {
        $this->_saveRecipes($model, isset($input['recipes']) ? $input['recipes'] : []);
        
        $this->_saveAdditionalBaskets($model, isset($input['baskets']) ? $input['baskets'] : []);
        
        $this->_saveIngredients($model, isset($input['ingredients']) ? $input['ingredients'] : []);
        
        $this->_saveComments($model, isset($input['status_comment']) ? $input['status_comment'] : '');
    }
    
    /**
     * @param \App\Models\Order $order
     *
     * @return \App\Models\Order
     */
    public function createTmpl(Order $order)
    {
        $tmpl = $order->replicate();
        
        $tmpl->parent_id = $order->id;
        $tmpl->status = Order::getStatusIdByName('tmpl');
        $tmpl->delivery_date = $this->_getDeliveryDateForTmplOrder($order);
        $tmpl->total = 0;
        
        $tmpl->save();
        
        $tmpl->baskets()->sync($order->baskets()->get(['id'])->pluck('id')->toArray());
        
        $order->load('recipes', 'ingredients');
        $relations = $order->getRelations();
        foreach ($relations as $relation_name => $relation) {
            foreach ($relation as $record) {
                $tmpl_record = $record->replicate();
                $tmpl_record->order_id = $tmpl->id;
                
                $tmpl_record->push();
            }
        }
        
        $this->addSystemOrderComment($tmpl, trans('messages.auto generated tmpl order'), 'tmpl');
        
        return $tmpl;
    }
    
    /**
     * @param \App\Models\Order $model
     * @param string            $comment
     * @param bool              $status
     */
    public function addSystemOrderComment(Order $model, $comment, $status = false)
    {
        $comment = new OrderComment(
            [
                'comment' => $comment,
                'status'  => $status,
            ]
        );
        
        $model->comments()->save($comment);
    }
    
    /**
     * @param \App\Models\Order $model
     * @param string            $comment
     * @param string            $status
     */
    public function addAdminOrderComment(Order $model, $comment, $status = '')
    {
        $comment = new OrderComment(
            [
                'user_id' => Sentry::getUser()->getId(),
                'comment' => $comment,
                'status'  => empty($status) ? $model->getStringStatus() : $status,
            ]
        );
        
        $model->comments()->save($comment);
    }
    
    /**
     * @param int $basket_id
     *
     * @return bool
     */
    public function checkCurrentWeekBasket($basket_id)
    {
        if (!WeeklyMenu::joinWeeklyMenuBaskets()->current()->where('weekly_menu_baskets.id', $basket_id)->first()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param \App\Models\Order $model
     * @param int               $basket_id
     */
    public function saveRecipes(Order $model, $basket_id)
    {
        $basket = WeeklyMenuBasket::with('main_recipes')->find($basket_id);
        
        $basket->main_recipes->each(
            function ($item, $index) use ($model) {
                $input = [
                    'basket_recipe_id' => $item->id,
                    'name'             => $item->getRecipeName(),
                ];
                $recipe = new OrderRecipe($input);
                
                $model->recipes()->save($recipe);
            }
        );
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $baskets
     */
    public function saveAdditionalBaskets(Order $model, $baskets)
    {
        $model->baskets()->sync($baskets);
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $ingredients
     */
    public function saveIngredients(Order $model, $ingredients)
    {
        foreach ($ingredients as $ingredient) {
            list($basket_recipe_id, $recipe_ingredient_id) = explode('_', $ingredient);
            
            $basket_recipe = BasketRecipe::with('recipe.home_ingredients')->find($basket_recipe_id);
            
            if ($basket_recipe) {
                $recipe_ingredient = $basket_recipe->recipe->home_ingredients->find($recipe_ingredient_id);
                
                if ($recipe_ingredient && $recipe_ingredient->ingredient->inSale()) {
                    $input = [
                        'basket_recipe_id' => $basket_recipe_id,
                        'ingredient_id'    => $recipe_ingredient->ingredient_id,
                        'name'             => $recipe_ingredient->ingredient->name,
                        'count'            => $recipe_ingredient->count,
                    ];
                    $ingredient = new OrderIngredient($input);
                    
                    $model->ingredients()->save($ingredient);
                }
            }
        }
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $input
     */
    private function _saveRecipes(Order $model, $input)
    {
        $data = isset($input['remove']) ? $input['remove'] : [];
        foreach ($data as $id) {
            try {
                $recipe = $model->recipes()->findOrFail($id);
                $recipe->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.recipe delete failure")." ".$id);
            }
        }
        
        $data = isset($input['old']) ? $input['old'] : [];
        foreach ($data as $id => $recipe) {
            try {
                $_recipe = OrderRecipe::findOrFail($id);
                $_recipe->update($recipe);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe update failure")." ".$recipe['name']
                );
            }
        }
        
        $data = isset($input['new']) ? $input['new'] : [];
        foreach ($data as $recipe) {
            try {
                $recipe = new OrderRecipe($recipe);
                $model->recipes()->save($recipe);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe save failure")." ".$recipe['name']
                );
            }
        }
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $baskets
     */
    private function _saveAdditionalBaskets(Order $model, $baskets)
    {
        $model->baskets()->sync($baskets);
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $input
     */
    private function _saveIngredients(Order $model, $input)
    {
        $data = isset($input['remove']) ? $input['remove'] : [];
        foreach ($data as $id) {
            try {
                $ingredient = $model->ingredients()->findOrFail($id);
                $ingredient->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.recipe delete failure")." ".$id);
            }
        }
        
        $data = isset($input['old']) ? $input['old'] : [];
        foreach ($data as $id => $ingredient) {
            try {
                $_recipe = OrderIngredient::findOrFail($id);
                $_recipe->update($ingredient);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe update failure")." ".$ingredient['name']
                );
            }
        }
        
        $data = isset($input['new']) ? $input['new'] : [];
        foreach ($data as $ingredient) {
            try {
                $ingredient = new OrderIngredient($ingredient);
                $model->ingredients()->save($ingredient);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe save failure")." ".$ingredient['name']
                );
            }
        }
    }
    
    /**
     * @param \App\Models\Order $model
     * @param string            $comment
     */
    private function _saveComments(Order $model, $comment)
    {
        if (!empty($comment)) {
            $this->addAdminOrderComment($model, $comment, $model->getStringStatus());
        }
    }
    
    /**
     * @param \App\Models\Order $parent_order
     *
     * @return string
     */
    private function _getDeliveryDateForTmplOrder(Order $parent_order)
    {
        $latest_tmpl_order = Order::whereId($parent_order->id)->orWhere('parent_id', $parent_order->id)
            ->orderBy('id', 'DESC')
            ->first();
        
        $delivery_date = $latest_tmpl_order->getDeliveryDate();
        
        return $delivery_date->addWeeks($parent_order->subscribe_period)->format('d-m-Y');
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
                        case 'delivery_date_from':
                            if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $value)) {
                                $value = Carbon::createFromFormat('d-m-Y', $value)->startOfDay()->format('Y-m-d H:i:s');
                                $list->where('orders.delivery_date', '>=', $value);
                            }
                            break;
                        case 'delivery_date_to':
                            if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $value)) {
                                $value = Carbon::createFromFormat('d-m-Y', $value)->endOfDay()->format('Y-m-d H:i:s');
                                $list->where('orders.delivery_date', '<=', $value);
                            }
                            break;
                    }
                }
            }
        }
    }
}