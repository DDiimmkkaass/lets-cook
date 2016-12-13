<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Order\OrderCommentCreateRequest;
use App\Http\Requests\Backend\Order\OrderRequest;
use App\Http\Requests\Backend\Order\OrderStatusChangeRequest;
use App\Models\Basket;
use App\Models\BasketRecipe;
use App\Models\City;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderBasket;
use App\Models\OrderComment;
use App\Models\OrderRecipe;
use App\Models\RecipeIngredient;
use App\Models\User;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;
use App\Services\CouponService;
use App\Services\OrderService;
use App\Services\WeeklyMenuService;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JavaScript;
use Meta;

/**
 * Class OrderController
 * @package App\Http\Controllers\Backend
 */
class OrderController extends BackendController
{
    
    /**
     * @var string
     */
    public $module = "order";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'order.read',
        'history' => 'order.read',
        'show'    => 'order.read',
        'create'  => 'order.write',
        'store'   => 'order.write',
        'edit'    => 'order.read',
        'update'  => 'order.write',
    ];
    
    /**
     * @var Order
     */
    public $model;
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * @var \App\Services\CouponService
     */
    private $couponService;
    
    /**
     * @var \App\Services\WeeklyMenuService
     */
    private $weeklyMenuService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\OrderService                    $orderService
     * @param \App\Services\CouponService                   $couponService
     * @param \App\Services\WeeklyMenuService               $weeklyMenuService
     */
    public function __construct(ResponseFactory $response, OrderService $orderService, CouponService $couponService, WeeklyMenuService $weeklyMenuService)
    {
        parent::__construct($response);
        
        $this->orderService = $orderService;
        $this->couponService = $couponService;
        $this->weeklyMenuService = $weeklyMenuService;
    
        Meta::title(trans('labels.orders'));
    
        $this->breadcrumbs(trans('labels.orders'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /order
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            return $this->orderService->tableIndex($request);
        }
        
        $this->data('statistic', $this->orderService->getOrdersStatistic());
        
        $this->data('page_title', trans('labels.orders'));
        $this->breadcrumbs(trans('labels.orders_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Display a listing of the resource.
     * GET /order
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function history(Request $request)
    {
        if ($request->get('draw')) {
            return $this->orderService->tableHistory($request);
        }
        
        $this->data('history', true);
        
        $this->data('page_title', trans('labels.orders_history'));
        $this->breadcrumbs(trans('labels.orders_history'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /recipe/create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $model = new Order();
        
        $this->data('model', $model);
        
        $this->data('page_title', trans('labels.order_creating'));
        
        $this->breadcrumbs(trans('labels.order_creating'));
        
        $this->_fillAdditionalTemplateData($model);
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /recipe
     *
     * @param \App\Http\Requests\Backend\Order\OrderRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OrderRequest $request)
    {
        try {
            $user = User::find($request->get('user_id'));
            
            DB::beginTransaction();
            
            $status = $this->_validCoupon($request, $user);
            
            if ($status !== true) {
                FlashMessages::add('error', $status);
                
                return redirect()->back();
            }
            
            $input = $this->orderService->prepareInputData($request);
            
            $model = new Order($input);
            $model->save();
            
            $this->orderService->saveRelationships($model, $input);
            
            list($subtotal, $total) = $this->orderService->getTotals($model);
            
            $model->subtotal = $subtotal;
            $model->total = $total;
            
            $model->save();
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            DB::rollBack();
            
            FlashMessages::add("error", trans('messages.save_error'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     * GET /order/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return $this->edit($id);
    }
    
    /**
     * Show the form for editing the specified resource.
     * GET /order/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {
            $model = Order::with(
                'ingredients',
                'ingredients.recipe',
                'main_basket',
                'additional_baskets',
                'comments',
                'user',
                'user.orders'
            )->findOrFail($id);
            
            $this->data('page_title', trans('labels.order').': #'.$model->id);
            
            $this->breadcrumbs(trans('labels.order_editing'));
            
            $this->_fillAdditionalTemplateData($model);
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /order/{id}
     *
     * @param int          $id
     * @param OrderRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, OrderRequest $request)
    {
        try {
            $model = Order::with('user')->findOrFail($id);
            
            DB::beginTransaction();
            
            $status = $this->_validCoupon($request, $model->user, $model);
            
            if ($status !== true) {
                FlashMessages::add('error', $status);
                
                return redirect()->back();
            }
            
            $input = $this->orderService->prepareInputData($request);
            
            $model->fill($input);
            
            $this->orderService->saveRelationships($model, $input);
            
            list($subtotal, $total) = $this->orderService->getTotals($model);
            
            $model->subtotal = $subtotal;
            $model->total = $total;
            
            $model->save();
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            DB::rollBack();
            
            FlashMessages::add("error", trans('messages.update_error'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * @param int                                                       $order_id
     * @param \App\Http\Requests\Backend\Order\OrderStatusChangeRequest $request
     *
     * @return array
     */
    public function updateStatus($order_id, OrderStatusChangeRequest $request)
    {
        try {
            $model = Order::findOrFail($order_id);
            
            $value = $request->get('status');
            
            $model->status = Order::getStatusIdByName($value);
            $model->save();
            
            $this->orderService->addAdminOrderComment($model, trans('messages.changed from orders list'));
            
            return [
                'status'  => 'success',
                'message' => trans('messages.field_value_successfully_saved'),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param \App\Http\Requests\Backend\Order\OrderCommentCreateRequest $request
     *
     * @return array
     */
    public function storeComment(OrderCommentCreateRequest $request)
    {
        try {
            $order = Order::findOrFail($request->get('order_id'));
            
            $comment = new OrderComment(
                [
                    'user_id' => $this->user->id,
                    'comment' => $request->get('order_comment'),
                ]
            );
            
            $order->comments()->save($comment);
            
            return [
                'status'  => 'success',
                'comment' => view('order.partials.comment', ['comment' => $comment])->render(),
                'message' => trans('messages.comment successfully added'),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $weekly_menu_id
     *
     * @return array
     */
    public function getWeeklyMenuBaskets($weekly_menu_id)
    {
        try {
            $html = '';
            
            $baskets = WeeklyMenuBasket::where('weekly_menu_id', $weekly_menu_id)->get();
            
            $baskets->sortBy(
                function ($item) {
                    $item->name = $item->getName().' ('.trans('labels.portions_lowercase').' '.$item->portions.')';
                    
                    return $item->name;
                }
            )->each(
                function ($item, $index) use (&$html) {
                    return $html .= view(
                        'partials.selects.option',
                        ['item' => ['id' => $item->id, 'name' => $item->name]]
                    )->render();
                }
            );
            
            return [
                'status' => 'success',
                'html'   => $html,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $basket_id
     *
     * @return array
     */
    public function getBasketRecipes($basket_id)
    {
        try {
            $html = view('partials.selects.option', ['item' => ['id' => '', 'name' => trans('labels.please_select')]])
                ->render();
            
            $recipes = BasketRecipe::where('weekly_menu_basket_id', $basket_id)->get();
            
            $recipes->sortBy(
                function ($item) {
                    return $item->getName();
                }
            )->each(
                function ($item, $index) use (&$html) {
                    return $html .= view(
                        'partials.selects.option',
                        ['item' => ['id' => $item->id, 'name' => $item->getName()]]
                    )->render();
                }
            );
            
            return [
                'status' => 'success',
                'html'   => $html,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $basket_id
     *
     * @return array
     */
    public function getBasketRecipesIngredients($basket_id)
    {
        try {
            $ingredients = [];
            
            $html = view('partials.selects.option', ['item' => ['id' => '', 'name' => trans('labels.please_select')]])
                ->render();
            
            $recipes = BasketRecipe::with('recipe.home_ingredients')
                ->where('weekly_menu_basket_id', $basket_id)
                ->get();
            
            foreach ($recipes as $recipe) {
                foreach ($recipe->recipe->home_ingredients as $ingredient) {
                    if ($ingredient->ingredient->inSale()) {
                        $ingredients[] = [
                            'ingredient_id'    => $ingredient->ingredient_id,
                            'name'             => $ingredient->ingredient->name,
                            'basket_recipe_id' => $recipe->id,
                            'recipe_name'      => $ingredient->recipe->name,
                        ];
                    }
                }
            }
            
            $ingredients = new Collection($ingredients);
            $ingredients = $ingredients->sortBy('name');
            
            foreach ($ingredients as $key => $ingredient) {
                $name = $ingredient['name'].' ('.$ingredient['recipe_name'].')';
                
                $html .= view(
                    'partials.selects.option',
                    [
                        'item' => [
                            'id'         => $key,
                            'name'       => $name,
                            'attributes' => [
                                'data-ingredient_id'    => $ingredient['ingredient_id'],
                                'data-basket_recipe_id' => $ingredient['basket_recipe_id'],
                            ],
                        ],
                    ]
                )->render();
            }
            
            return [
                'status' => 'success',
                'html'   => $html,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $recipe_id
     *
     * @return array
     */
    public function getRecipeRow($recipe_id)
    {
        try {
            $model = BasketRecipe::with('recipe')->findOrFail($recipe_id);
            
            return [
                'status'   => 'success',
                'position' => $model->position,
                'html'     => view('views.'.$this->module.'.partials.recipe_row', compact('model'))->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $basket_recipe_id
     * @param int $ingredient_id
     *
     * @return array
     */
    public function getIngredientRow($basket_recipe_id, $ingredient_id)
    {
        try {
            $basket_recipe = BasketRecipe::with('recipe')->findOrFail($basket_recipe_id);
            
            $model = Ingredient::joinRecipeIngredients()->joinSaleUnit()
                ->inSales()
                ->where('ingredients.id', $ingredient_id)
                ->where('recipe_ingredients.recipe_id', $basket_recipe->recipe_id)
                ->where('recipe_ingredients.type', RecipeIngredient::getTypeIdByName('home'))
                ->select(
                    'ingredients.id',
                    'ingredients.name',
                    'ingredients.image',
                    DB::raw('units.name as unit_name'),
                    'recipe_ingredients.count'
                )
                ->firstOrFail();
            
            return [
                'status' => 'success',
                'html'   => view('views.'.$this->module.'.partials.ingredient_row')
                    ->with(compact('basket_recipe', 'model'))
                    ->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $basket_recipe_id
     *
     * @return array
     */
    public function basketRecipesOrdersCount($basket_recipe_id)
    {
        try {
            $orders = OrderRecipe::where('basket_recipe_id', $basket_recipe_id)->count();
            
            if ($orders > 0) {
                return [
                    'status'  => 'warning',
                    'message' => trans('messages.you can not delete this recipe as it is used in order'),
                ];
            } else {
                return ['status' => 'success'];
            }
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $weekly_menu_basket_id
     *
     * @return array
     */
    public function weeklyMenuBasketOrdersCount($weekly_menu_basket_id)
    {
        try {
            $orders = OrderBasket::where('weekly_menu_basket_id', $weekly_menu_basket_id)->count();
            
            if ($orders > 0) {
                return [
                    'status'  => 'warning',
                    'message' => trans('messages.you can not delete this basket as it is used in order'),
                ];
            } else {
                return ['status' => 'success'];
            }
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * fill additional template data
     *
     * @param \App\Models\Order $model
     */
    private function _fillAdditionalTemplateData(Order $model)
    {
        $this->data('users', User::joinInfo()->active()
            ->orWhere('users.id', '=', $model->user_id)
            ->orderBy('user_info.full_name')
            ->get([
                'users.id',
                'users.email',
                'user_info.user_id',
                'user_info.full_name',
                'user_info.phone',
                'user_info.additional_phone',
                'user_info.city_id',
                'user_info.city_name',
                'user_info.address',
                'user_info.comment',
            ])
        );
        
        $payment_methods = [];
        foreach (Order::getPaymentMethods() as $id => $payment_method) {
            $payment_methods[$id] = trans('labels.payment_method_'.$payment_method);
        }
        $this->data('payment_methods', $payment_methods);
        
        $statuses = [];
        foreach (Order::getStatuses() as $id => $status) {
            $statuses[$id] = trans('labels.order_status_'.$status);
        }
        $this->data('statuses', $statuses);
        
        $delivery_times = [];
        foreach (config('order.delivery_times') as $delivery_time) {
            $delivery_times[$delivery_time] = $delivery_time;
        }
        $this->data('delivery_times', $delivery_times);
        
        $cities = ['' => trans('labels.another')];
        foreach (City::positionSorted()->nameSorted()->get() as $city) {
            $cities[$city->id] = $city->name;
        }
        $this->data('cities', $cities);
        
        $recipes = $model->recipes()
            ->joinBasketRecipe()
            ->joinRecipe()
            ->with('recipe', 'recipe.recipe', 'recipe.weekly_menu_basket')
            ->existInWeeklyMenu()
            ->get(
                [
                    'order_recipes.id',
                    'order_recipes.basket_recipe_id',
                    'basket_recipes.recipe_id',
                    'recipes.name',
                    'recipes.image',
                    'recipes.portions',
                ]
            );
        $recipes = $recipes->sortBy(
            function ($item) {
                return $item->recipe->getName();
            }
        );
        $this->data('recipes', $recipes);
        
        $weekly_menus = ['' => trans('labels.please_select')];
        foreach (WeeklyMenu::with('baskets')->active()->get() as $weekly_menu) {
            $weekly_menus[$weekly_menu->id] = $weekly_menu->getName();
        }
        $this->data('weekly_menus', $weekly_menus);
        
        if ($model->main_basket) {
            $basket_id = $model->main_basket->weekly_menu_basket->id;
            $weekly_menu_id = $model->main_basket->weekly_menu_basket->weekly_menu_id;
            $recipes_count = count($recipes);
            
            $baskets = [
                $basket_id => $model->main_basket->weekly_menu_basket->getName().' ('.
                    trans('labels.portions_lowercase').' '.$model->main_basket->weekly_menu_basket->portions.')',
            ];
        } else {
            $basket_id = null;
            $weekly_menu_id = null;
            $recipes_count = 0;
            
            $baskets = [];
        }
        $this->data('basket_id', $basket_id);
        $this->data('weekly_menu_id', $weekly_menu_id);
        $this->data('recipes_count', $recipes_count);
        $this->data('baskets', $baskets);
        
        $this->data('additional_baskets', Basket::additional()->positionSorted()->get());
        
        $this->data('selected_baskets', $model->additional_baskets->keyBy('basket_id')->toArray());
        
        $user_coupons = ['' => trans('labels.not_set')];
        if ($model->exists) {
            $user = $model->user;
            $user_id = $user->id;
            $default = false;
            
            $coupons = $user->coupons()->with(
                [
                    'orders' => function ($query) use ($user_id) {
                        $query->whereUserId($user_id);
                    },
                ]
            )->get()->keyBy('id');
    
            $_coupons = $coupons->filter(
                function ($item) use ($model, $user, &$default) {
                    if ($item->available($user)) {
                        $default = $item->default ? true : $default;
        
                        return true;
                    }
    
                    return false;
                }
            );
            
            if (!$default) {
                $coupon = $_coupons->last();
    
                if ($coupon) {
                    $coupon->default = true;
                    $coupon->save();
    
                    $coupons->put($coupon->id, $coupon);
                }
            }
            
            foreach ($coupons as $coupon) {
                if ($coupon->available($model->user) || $coupon->coupon_id == $model->coupon_id) {
                    $user_coupons[$coupon->coupon_id] = $coupon->getName();
                }
            }
        }
        $this->data('user_coupons', $user_coupons);
        
        JavaScript::put(
            [
                'recipes_for_days' => config('weekly_menu.recipes_for_days'),
            ]
        );
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @param \App\Models\Order|null   $order
     *
     * @return bool
     */
    private function _validCoupon(Request $request, User $user, $order = null)
    {
        if ($request->get('coupon_code')) {
            $status = $this->couponService->available($request->get('coupon_code'), $user);
            
            if ($status !== true) {
                return $status;
            }
            
            $this->couponService->saveUserCoupon($user, $request->get('coupon_code'));
        } else {
            $coupon_id = $request->get('coupon_id');
            
            if ($coupon_id && (!$order || $coupon_id != $order->coupon_id)) {
                $status = $this->couponService->availableById($coupon_id, $user);
                
                if ($status !== true) {
                    return $status;
                }
            }
        }
        
        return true;
    }
}