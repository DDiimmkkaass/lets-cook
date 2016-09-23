<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.09.16
 * Time: 15:31
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\Order\OrderCreateRequest;
use App\Http\Requests\Frontend\Order\OrderUpdateRequest;
use App\Models\Basket;
use App\Models\City;
use App\Models\Order;
use App\Models\WeeklyMenuBasket;
use App\Services\AuthService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\WeeklyMenuService;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Http\Request;

/**
 * Class OrderController
 * @package App\Http\Controllers\Frontend
 */
class OrderController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'order';
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * @var \App\Services\WeeklyMenuService
     */
    private $weeklyMenuService;
    
    /**
     * @var \App\Services\PaymentService
     */
    private $paymentService;
    
    /**
     * @var \App\Services\AuthService
     */
    private $authService;
    
    /**
     * OrderController constructor.
     *
     * @param \App\Services\OrderService      $orderService
     * @param \App\Services\WeeklyMenuService $weeklyMenuService
     * @param \App\Services\PaymentService    $paymentService
     * @param \App\Services\AuthService       $authService
     */
    public function __construct(
        OrderService $orderService,
        WeeklyMenuService $weeklyMenuService,
        PaymentService $paymentService,
        AuthService $authService
    ) {
        parent::__construct();
        
        $this->orderService = $orderService;
        $this->weeklyMenuService = $weeklyMenuService;
        $this->paymentService = $paymentService;
        $this->authService = $authService;
        
        $this->middleware('auth.check_email_exists', ['only' => ['store']]);
        $this->middleware('order.before_finalisation', ['only' => ['update']]);
    }
    
    /**
     * @param int $basket_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index($basket_id)
    {
        abort_if(!$this->weeklyMenuService->checkActiveWeeksBasket($basket_id), 404);
        
        $basket = WeeklyMenuBasket::with(
            ['recipes', 'recipes.recipe.ingredients', 'recipes.recipe.home_ingredients']
        )->joinWeeklyMenu()
            ->select('weekly_menu_baskets.*', 'weekly_menus.year', 'weekly_menus.week')
            ->find($basket_id);
        
        $this->data('basket', $basket);
        $this->data('selected_baskets', collect());
        
        $this->_fillAdditionalTemplateData($basket->year, $basket->week);
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @param int $order_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function repeat($order_id)
    {
        $repeat_order = $this->orderService->getOrder($order_id);
        
        $basket = $this->orderService->getSameActiveBasket($repeat_order);
        
        if (!$basket) {
            FlashMessages::add('error', trans('front_messages.no same basket on this week'));
            
            return redirect()->back();
        }
        
        $this->data('basket', $basket);
        $this->data('repeat_order', $repeat_order);
        $this->data('selected_baskets', $repeat_order->additional_baskets->pluck('basket_id'));
        
        $this->_fillAdditionalTemplateData($basket->year, $basket->week);
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Order\OrderCreateRequest $request
     *
     * @return array
     */
    public function store(OrderCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            
            abort_if(!$this->weeklyMenuService->checkActiveWeeksBasket($request->get('basket_id', 0)), 404);
            
            if (!$this->user) {
                $this->user = $this->authService->quickRegister($request->all());
            }
            
            $input = $this->orderService->prepareFrontInputData($request, $this->user);
            
            $model = new Order($input);
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            $model->total = $model->getTotal();
            
            $model->save();
            
            DB::commit();
            
            if ($model->paymentMethod('online')) {
                $provider = $this->paymentService->getProvider();
                
                $html = $provider->getForm($model);
            }
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.your order successfully created'),
                'html'    => isset($html) ? $html : '',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param $order_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($order_id)
    {
        $order = $this->orderService->getOrder($order_id);
        
        abort_if(!$order->user_id == $this->user->id, 404);
        
        $weekly_menu = $order->main_basket->weekly_menu_basket->weekly_menu;
        
        $this->data('weekly_menu', $weekly_menu);
        $this->data('data_tab', 'my-orders-edit');
        $this->data('profile_css_class', 'order-edit');
        
        $this->data('order', $order);
        
        $this->_fillAdditionalTemplateData($weekly_menu->year, $weekly_menu->week);
        
        return $this->render($this->module.'.edit');
    }
    
    /**
     * @param int                                                  $order_id
     * @param \App\Http\Requests\Frontend\Order\OrderUpdateRequest $request
     *
     * @return array
     */
    public function update($order_id, OrderUpdateRequest $request)
    {
        $model = $this->orderService->getOrder($order_id);
        
        abort_if($model->user_id != $this->user->id, 404);
        
        try {
            DB::beginTransaction();
            
            $input = $this->orderService->prepareEditFrontInputData($request);
    
            $model->fill($input);
            $model->save();
            
            $this->_saveEditRelationships($model, $request);
            
            $model->total = $model->getTotal();
            
            $model->save();
            
            DB::commit();
            
            if ($model->paymentMethod('online') && $model->status == Order::getStatusIdByName('changed')) {
                $provider = $this->paymentService->getProvider();
    
                $html = $provider->getForm($model);
            }
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.your order successfully updated'),
                'html'    => isset($html) ? $html : '',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * fill additional template data
     *
     * @param int $year
     * @param int $week
     */
    private function _fillAdditionalTemplateData($year, $week)
    {
        $additional_baskets = Basket::with('recipes')->additional()->positionSorted()->get();
        $this->data('additional_baskets', $additional_baskets);
        
        $this->data('delivery_dates', $this->weeklyMenuService->getDeliveryDates($year, $week));
        
        $this->data('delivery_times', config('order.delivery_times'));
        
        $payment_methods = [];
        foreach (Order::getPaymentMethods() as $id => $payment_method) {
            $payment_methods[$id] = trans('front_labels.payment_method_'.$payment_method);
        }
        $this->data('payment_methods', $payment_methods);
        
        $subscribe_periods = [];
        foreach (Order::getSubscribePeriods() as $subscribe_period) {
            $subscribe_periods[$subscribe_period] = trans_choice(
                'front_labels.subscribe_period_label',
                $subscribe_period
            );
        }
        $this->data('subscribe_periods', $subscribe_periods);
        
        $this->data('cities', City::positionSorted()->nameSorted()->get());
    }
    
    /**
     * @param \App\Models\Order        $model
     * @param \Illuminate\Http\Request $request
     */
    private function _saveRelationships(Order $model, Request $request)
    {
        $this->orderService->saveRecipes($model, $request->get('basket_id'));
        
        $this->orderService->saveMainBasket($model, $request->get('basket_id'), $model->recipes->count());
        
        $this->orderService->saveAdditionalBaskets($model, $request->get('baskets', []));
        
        $this->orderService->saveIngredients($model, $request->get('ingredients', []));
        
        $this->orderService->addSystemOrderComment($model, trans('front_messages.new order'));
    }
    
    /**
     * @param \App\Models\Order        $model
     * @param \Illuminate\Http\Request $request
     */
    private function _saveEditRelationships(Order $model, Request $request)
    {
        if ($model->main_basket->weekly_menu_basket_id != $request->get('basket_id')) {
            $model->ingredients()->delete();
            $model->recipes()->delete();
    
            $this->orderService->saveRecipes($model, $request->get('basket_id'), [], $request->get('recipes_count'));
        }
        
        $this->orderService->saveMainBasket($model, $request->get('basket_id'), $request->get('recipes_count'));
        
        $this->orderService->saveAdditionalBaskets($model, $request->get('baskets', []));
        
        $this->orderService->addSystemOrderComment($model, trans('front_messages.user change the order'));
    }
}