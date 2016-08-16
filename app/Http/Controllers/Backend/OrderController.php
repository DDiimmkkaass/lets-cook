<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Order\OrderUpdateRequest;
use App\Models\Basket;
use App\Models\BasketRecipe;
use App\Models\City;
use App\Models\Group;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderRecipe;
use App\Models\WeeklyMenuBasket;
use App\Services\OrderService;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

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
        'index'  => 'order.read',
        'show'   => 'order.read',
        'edit'   => 'order.read',
        'update' => 'order.write',
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
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\OrderService                    $orderService
     */
    public function __construct(ResponseFactory $response, OrderService $orderService)
    {
        parent::__construct($response);
        
        $this->orderService = $orderService;
        
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
            return $this->orderService->table();
        }
        
        $this->data('page_title', trans('labels.orders'));
        $this->breadcrumbs(trans('labels.orders_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Display the specified resource.
     * GET /order/{id}
     *
     * @param  int $id
     *
     * @return Response
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
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Order::with('ingredients', 'baskets')->findOrFail($id);
            
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
     * @param  int               $id
     * @param OrderUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, OrderUpdateRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $model = Order::findOrFail($id);
    
            if (!$model->editable()) {
                FlashMessages::add('warning', trans('messages.order has un editable status error'));
        
                return redirect()->route('admin.'.$this->module.'.index');
            }
            
            $input = $this->orderService->prepareUpdateData($request);
            
            $model->fill($input);
            
            $this->orderService->saveRelationships($model, $input);
    
            $model->total = $model->getTotal();
            
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
     * @param int $recipe_id
     *
     * @return array
     */
    public function getRecipeRow($recipe_id)
    {
        try {
            $model = BasketRecipe::with('recipe')->findOrFail($recipe_id);
            
            return [
                'status' => 'success',
                'html'   => view('views.'.$this->module.'.partials.recipe_row', compact('model'))->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $ingredient_id
     *
     * @return array
     */
    public function getIngredientRow($ingredient_id)
    {
        try {
            $model = Ingredient::inSales()->whereId($ingredient_id)->firstOrFail();
            
            return [
                'status' => 'success',
                'html'   => view('views.'.$this->module.'.partials.ingredient_row', compact('model'))->render(),
            ];
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
        $users = [];
        foreach (Group::with('users')->clients()->first()->users as $user) {
            $users[$user->id] = $user->getFullName();
        }
        $this->data('users', $users);
        
        $types = [];
        foreach (Order::getTypes() as $id => $type) {
            $types[$id] = trans('labels.order_type_'.$type);
        }
        $this->data('types', $types);
        
        $subscribe_periods = [];
        foreach (Order::getSubscribePeriods() as $subscribe_period) {
            $subscribe_periods[$subscribe_period] = trans_choice('labels.subscribe_period_label', $subscribe_period);
        }
        $this->data('subscribe_periods', $subscribe_periods);
    
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
    
        $delivery_times= [];
        foreach (config('order.delivery_times') as $delivery_time) {
            $delivery_times[$delivery_time] = $delivery_time;
        }
        $this->data('delivery_times', $delivery_times);
        
        $cities = ['' => trans('labels.another')];
        foreach (City::all() as $city) {
            $cities[$city->id] = $city->name;
        }
        $this->data('cities', $cities);
        
        $weekly_menu_basket_id = $model->recipes()->first()->recipe->weekly_menu_basket_id;
        $basket = WeeklyMenuBasket::with('recipes')->find($weekly_menu_basket_id);
        $this->data('basket', $basket);
        
        $basket_recipes = ['' => trans('labels.please_select')];
        foreach ($basket->recipes as $recipe) {
            $basket_recipes[$recipe->id] = $recipe->recipe->name;
        }
        $this->data('basket_recipes', $basket_recipes);
        
        $recipes = OrderRecipe::where('order_recipes.order_id', $model->id)->joinBasketRecipes()->joinRecipes()->get(
            [
                'order_recipes.id',
                'order_recipes.basket_recipe_id',
                'basket_recipes.recipe_id',
                'recipes.name',
                'recipes.image',
                'recipes.portions',
            ]
        );
        $this->data('recipes', $recipes);
    
        $this->data('additional_baskets', Basket::additional()->get());
        
        $this->data('selected_baskets', $model->baskets->keyBy('id')->toArray());
    }
}