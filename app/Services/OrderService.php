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
use App\Models\BasketSubscribe;
use App\Models\Coupon;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderBasket;
use App\Models\OrderComment;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use App\Models\User;
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
        $list = Order::with('user', 'main_basket', 'additional_baskets')
            ->select(
                'id',
                'full_name',
                'user_id',
                'phone',
                'additional_phone',
                DB::raw('1 as baskets_list'),
                'payment_method',
                'total',
                'status',
                DB::raw('4 as coupon'),
                'delivery_date',
                'delivery_time',
                'city_id',
                'address',
                'comment'
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
                'phone',
                function ($model) {
                    return view('order.datatables.user_phones', ['model' => $model])->render();
                }
            )
            ->editColumn(
                'baskets_list',
                function ($model) {
                    return view('order.datatables.baskets_list', ['model' => $model])->render();
                }
            )
            ->editColumn(
                'total',
                function ($model) {
                    return $model->total.'<br>'.
                    ($model->paymentMethod('cash') ?
                        '<div class="red">'.trans('labels.for_courier').'</div>' :
                        ''
                    );
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view('order.datatables.status_changer', ['model' => $model])->render();
                }
            )
            ->editColumn(
                'coupon',
                function ($model) {
                    return $model->getCouponCode();
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
                    
                    return '<div class="text-center">'.$html.' '.$model->delivery_time.'<div>';
                }
            )
            ->editColumn(
                'address',
                function ($model) {
                    return view('order.datatables.address', ['model' => $model])->render();
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
            ->removeColumn('user')
            ->removeColumn('main_basket')
            ->removeColumn('additional_baskets')
            ->removeColumn('user_id')
            ->removeColumn('additional_phone')
            ->removeColumn('payment_method')
            ->removeColumn('comment')
            ->removeColumn('city_id')
            ->removeColumn('delivery_time')
            ->make();
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
        
        $statistic['additional_baskets'] = OrderBasket::additional()
            ->joinBasket()
            ->whereIn('order_baskets.order_id', $orders->pluck('id'))
            ->select(
                'order_baskets.basket_id',
                'baskets.name',
                DB::raw('SUM(baskets.price) as total'),
                DB::raw('count(order_baskets.basket_id) as count')
            )
            ->groupBy('order_baskets.basket_id')
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
        $data['full_name'] = $request->get('full_name');
        $data['email'] = $request->get('email');
        $data['phone'] = $request->get('phone');
        
        $data['basket_id'] = $request->get('basket_id');
        
        $data['payment_method'] = $request->get('payment_method');
        
        $data['delivery_date'] = $request->get('delivery_date');
        $data['delivery_time'] = $request->get('delivery_time');
        
        $data['city_id'] = $request->get('city_id');
        $data['city_name'] = empty($data['city_id']) ? $request->get('city_name') : '';
        $data['address'] = $request->get('address');
        $data['comment'] = $request->get('comment');
        
        $data['verify_call'] = empty($request->get('verify_call', null)) ? 0 : 1;
        
        $data['status'] = Order::getStatusIdByName('changed');
        
        $data['coupon_id'] = $request->get('coupon_code') ?
            Coupon::whereCode($request->get('coupon_code'))->first()->id :
            null;
        
        return $data;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function prepareEditFrontInputData(Request $request)
    {
        $data = [];
        
        $data['basket_id'] = $request->get('basket_id');
        
        $data['payment_method'] = $request->get('payment_method');
        
        $data['delivery_date'] = $request->get('delivery_date');
        $data['delivery_time'] = $request->get('delivery_time');
        
        $data['city_id'] = $request->get('city_id');
        $data['city_name'] = empty($data['city_id']) ? $request->get('city_name') : '';
        $data['address'] = $request->get('address');
        $data['comment'] = $request->get('comment');
        
        return $data;
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $input
     */
    public function saveRelationships(Order $model, $input)
    {
        $recipes = isset($input['recipes']) ? $input['recipes'] : [];
        
        $this->_saveRecipes($model, $recipes);
        
        $this->saveMainBasket($model, $input['basket_id'], $model->recipes->count());
        
        $this->saveAdditionalBaskets($model, isset($input['baskets']) ? $input['baskets'] : []);
        
        $this->_saveIngredients($model, isset($input['ingredients']) ? $input['ingredients'] : []);
        
        $this->_saveComments($model, isset($input['status_comment']) ? $input['status_comment'] : '');
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
                if (!$model->recipes()->where('basket_recipe_id', $recipe['basket_recipe_id'])->first()) {
                    $recipe = new OrderRecipe($recipe);
                    $model->recipes()->save($recipe);
                }
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
     * @param int               $weekly_menu_basket_id
     * @param int               $days
     *
     * @return \App\Models\OrderBasket
     */
    public function saveMainBasket(Order $model, $weekly_menu_basket_id, $days)
    {
        $weekly_menu_basket = WeeklyMenuBasket::with('basket')->findOrFail($weekly_menu_basket_id);
        
        $main_basket = $model->main_basket()->first();
        if (!$main_basket) {
            $main_basket = new OrderBasket(
                [
                    'weekly_menu_basket_id' => $weekly_menu_basket_id,
                ]
            );
        } else {
            $main_basket->weekly_menu_basket_id = $weekly_menu_basket_id;
        }
        $main_basket->name = $weekly_menu_basket->getName();
        $main_basket->price = $weekly_menu_basket->getPriceInOrder($days);
        
        $model->main_basket()->save($main_basket);
        
        return $main_basket;
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $baskets
     */
    public function saveAdditionalBaskets(Order $model, $baskets)
    {
        foreach ($baskets as $basket_id) {
            if (!$model->additional_baskets()->whereBasketId($basket_id)->count()) {
                $basket = Basket::findOrFail($basket_id);
                
                $order_basket = new OrderBasket(
                    [
                        'basket_id' => $basket_id,
                        'name'      => $basket->name,
                    ]
                );
                $order_basket->price = $basket->getPrice();
                
                $model->additional_baskets()->save($order_basket);
            }
        }
        
        $model->additional_baskets()->whereNotIn('basket_id', $baskets)->delete();
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
                $_ingredient = Ingredient::findOrFail($ingredient['ingredient_id']);
                
                $ingredient = new OrderIngredient($ingredient);
                $ingredient->price = $_ingredient->sale_price;
                
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
     * @param \App\Models\BasketSubscribe $subscribe
     *
     * @return \App\Models\Order
     */
    public function createTmpl(BasketSubscribe $subscribe)
    {
        $tmpl = new Order(
            [
                'user_id'          => $subscribe->user_id,
                'payment_method'   => $subscribe->payment_method,
                'delivery_date'    => $this->_getDeliveryDateForTmplOrder($subscribe),
                'delivery_time'    => $subscribe->delivery_time,
                'full_name'        => $subscribe->user->full_name,
                'email'            => $subscribe->user->email,
                'phone'            => $subscribe->user->phone,
                'additional_phone' => $subscribe->user->additional_phone,
                'city_id'          => $subscribe->user->city_id,
                'city_name'        => $subscribe->user->city_name,
                'address'          => $subscribe->user->address,
            ]
        );
        
        $tmpl->status = Order::getStatusIdByName('tmpl');
        $tmpl->total = 0;
        
        $tmpl->save();
        
        $this->tmplAddMainBasket($tmpl, $subscribe);
        
        $this->tmplAddAdditionalBaskets($tmpl, $subscribe);
        
        $this->addSystemOrderComment($tmpl, trans('messages.auto generated tmpl order'), 'tmpl');
        
        return $tmpl;
    }
    
    /**
     * @param \App\Models\BasketSubscribe $subscribe
     *
     * @return string
     */
    private function _getDeliveryDateForTmplOrder(BasketSubscribe $subscribe)
    {
        $latest_tmpl_order = Order::ofStatus('tmpl')->whereUserId($subscribe->user_id)
            ->orderBy('delivery_date', 'DESC')
            ->first();
        
        if (!$latest_tmpl_order) {
            $latest_tmpl_order = Order::joinOrderBaskets()
                ->joinWeeklyMenuBasket()
                ->whereUserId($subscribe->user_id)
                ->where('weekly_menu_baskets.basket_id', $subscribe->basket_id)
                ->orderBy('orders.updated_at', 'DESC')
                ->first();
        }
        
        if (!$latest_tmpl_order) {
            $delivery_date = Carbon::now()->endOfWeek()->startOfDay();
        } else {
            $delivery_date = $latest_tmpl_order->getDeliveryDate();
        }
        
        $delivery_date = $delivery_date->dayOfWeek > $subscribe->delivery_date ?
            $delivery_date->subDay() :
            (
            $delivery_date->dayOfWeek < $subscribe->delivery_date ?
                $delivery_date->addDay() :
                $delivery_date
            );
        
        return $delivery_date->addWeeks($subscribe->subscribe_period)->format('d-m-Y');
    }
    
    /**
     * @param Order           $tmpl
     * @param BasketSubscribe $subscribe
     */
    public function tmplAddMainBasket(Order $tmpl, BasketSubscribe $subscribe)
    {
        $week = $tmpl->getDeliveryDate()->startOfWeek();
        
        if ($subscribe->delivery_date == 0) {
            $week->addWeek();
        }
        
        $basket = WeeklyMenuBasket::with('recipes')->joinWeeklyMenu()
            ->where(['weekly_menus.year' => $week->year, 'weekly_menus.week' => $week->weekOfYear])
            ->whereBasketId($subscribe->basket_id)
            ->wherePortions($subscribe->portions)
            ->select('weekly_menu_baskets.*', 'weekly_menus.year', 'weekly_menus.week')
            ->first();
        
        if ($basket) {
            OrderBasket::create(
                [
                    'order_id'              => $tmpl->id,
                    'weekly_menu_basket_id' => $basket->id,
                    'name'                  => $basket->getName(),
                ]
            );
            
            $this->saveRecipes($tmpl, $basket->id, [], $subscribe->portions);
        }
        
        return $basket;
    }
    
    /**
     * @param \App\Models\Order $model
     * @param int               $basket_id
     * @param array             $recipes
     * @param int               $recipes_count
     */
    public function saveRecipes(Order $model, $basket_id, $recipes = [], $recipes_count = 0)
    {
        $basket = WeeklyMenuBasket::with('recipes')->find($basket_id);
        
        $indexes = null;
        if ($recipes_count > 0) {
            $recipes_for_days = config('weekly_menu.recipes_for_days');
            
            $indexes = isset($recipes_for_days[$recipes_count]) ? $recipes_for_days[$recipes_count] : (int) $recipes_count;
        }
        
        $basket->recipes->each(
            function ($item, $index) use ($model, $recipes, $recipes_count, $indexes) {
                $add = false;
                
                $index += 1;
                
                if (isset($recipes[$item->id])) {
                    $add = true;
                }
                
                if (is_array($indexes) && in_array($index, $indexes)) {
                    $add = true;
                }
                
                if (is_int($indexes) && ($index <= $indexes)) {
                    $add = true;
                }
                
                if ($add) {
                    $input = [
                        'basket_recipe_id' => $item->id,
                        'name'             => $item->getRecipeName(),
                    ];
                    $recipe = new OrderRecipe($input);
                    
                    $model->recipes()->save($recipe);
                }
            }
        );
    }
    
    /**
     * @param \App\Models\Order           $tmpl
     * @param \App\Models\BasketSubscribe $subscribe
     */
    public function tmplAddAdditionalBaskets(Order $tmpl, BasketSubscribe $subscribe)
    {
        foreach ($subscribe->additional_baskets as $basket) {
            OrderBasket::create(
                [
                    'order_id'  => $tmpl->id,
                    'basket_id' => $basket->id,
                    'name'      => $basket->getName(),
                ]
            );
        }
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
                    $ingredient = new OrderIngredient(
                        [
                            'basket_recipe_id' => $basket_recipe_id,
                            'ingredient_id'    => $recipe_ingredient->ingredient_id,
                            'name'             => $recipe_ingredient->ingredient->name,
                            'count'            => $recipe_ingredient->count,
                        ]
                    );
                    $ingredient->price = $recipe_ingredient->ingredient->sale_price;
                    
                    $model->ingredients()->save($ingredient);
                }
            }
        }
    }
    
    /**
     * @param \App\Models\Order $order
     */
    public function updatePrices(Order $order)
    {
        $order->main_basket->price = $order->main_basket->weekly_menu_basket->getPrice(null, $order->recipes->count());
        $order->main_basket->save();
        
        foreach ($order->additional_baskets as $basket) {
            $basket->price = $basket->basket->getPrice();
            $basket->save();
        }
    }
    
    /**
     * @param int $order_id
     *
     * @return Order|null
     */
    public function getOrder($order_id)
    {
        return Order::with('main_basket', 'additional_baskets', 'main_basket.weekly_menu_basket.weekly_menu')
            ->notOfStatus(['archived', 'deleted'])
            ->whereId($order_id)
            ->first();
    }
    
    /**
     * @param $repeat_order
     *
     * @return WeeklyMenuBasket|null
     */
    public function getSameActiveBasket($repeat_order)
    {
        $active_week = active_week_menu_week();
        
        $basket = WeeklyMenuBasket::with(
            ['recipes', 'recipes.recipe.ingredients', 'recipes.recipe.home_ingredients']
        )->joinWeeklyMenu()
            ->where(['weekly_menus.year' => $active_week->year, 'weekly_menus.week' => $active_week->weekOfYear])
            ->whereBasketId($repeat_order->main_basket->weekly_menu_basket->basket_id)
            ->wherePortions($repeat_order->main_basket->getPortions())
            ->select('weekly_menu_baskets.*', 'weekly_menus.year', 'weekly_menus.week')
            ->first();
        
        return $basket;
    }
    
    /**
     * @param \App\Models\Order $order
     *
     * @return array
     */
    public function getTotals(Order $order)
    {
        $subtotal = $total = 0;
        
        $ingredients_price = $order->ingredients()->get(['count', 'price'])->reduce(
            function ($_subtotal, $item) {
                return $_subtotal + $item->getPriceInOrder();
            }
        );
        
        $main_basket_price = $order->main_basket()->first()->price;
        
        $additional_baskets_price = $order->additional_baskets()->get()->sum('price');
        
        $subtotal += $ingredients_price + $main_basket_price + $additional_baskets_price;
        
        if ($order->coupon_id) {
            $couponService = new CouponService();
            
            $total = $ingredients_price +
                $couponService->calculateOrderTotal($order, $main_basket_price, $additional_baskets_price);
        } else {
            $total = $subtotal;
        }
        
        return [$subtotal, $total];
    }
}