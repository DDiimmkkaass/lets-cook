<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.09.16
 * Time: 15:31
 */

namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\NewOrder;
use App\Http\Requests\Frontend\Order\OrderCreateRequest;
use App\Http\Requests\Frontend\Order\OrderUpdateRequest;
use App\Models\Basket;
use App\Models\BasketSubscribe;
use App\Models\City;
use App\Models\Order;
use App\Models\WeeklyMenuBasket;
use App\Services\AuthService;
use App\Services\CouponService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\SubscribeService;
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
     * @var \App\Services\SubscribeService
     */
    private $subscribeService;
    
    /**
     * @var \App\Services\CouponService
     */
    private $couponService;
    
    /**
     * OrderController constructor.
     *
     * @param \App\Services\OrderService      $orderService
     * @param \App\Services\WeeklyMenuService $weeklyMenuService
     * @param \App\Services\PaymentService    $paymentService
     * @param \App\Services\AuthService       $authService
     * @param \App\Services\SubscribeService  $subscribeService
     * @param \App\Services\CouponService     $couponService
     */
    public function __construct(
        OrderService $orderService,
        WeeklyMenuService $weeklyMenuService,
        PaymentService $paymentService,
        AuthService $authService,
        SubscribeService $subscribeService,
        CouponService $couponService
    ) {
        parent::__construct();
        
        $this->orderService = $orderService;
        $this->weeklyMenuService = $weeklyMenuService;
        $this->paymentService = $paymentService;
        $this->authService = $authService;
        $this->subscribeService = $subscribeService;
        $this->couponService = $couponService;
        
        $this->middleware('auth.check_email_exists', ['only' => ['store']]);
        $this->middleware('user.order.editable', ['only' => ['edit', 'update']]);
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Order\OrderCreateRequest $request
     *
     * @return array
     */
    public function store(OrderCreateRequest $request)
    {
        try {
            abort_if(!$this->weeklyMenuService->checkActiveWeeksBasket($request->get('basket_id', 0)), 404);
            
            if (!$this->user) {
                $this->user = $this->authService->quickRegister($request->all());
                
                if (variable('registration_coupon_discount')) {
                    $coupon = $this->couponService->giveRegistrationCoupon($this->user);
                }
            }
            
            $status = $this->_validCoupon($request);
            
            if ($status !== true) {
                return [
                    'status'  => 'error',
                    'message' => $status,
                ];
            }
            
            DB::beginTransaction();
            
            $input = $this->orderService->prepareFrontInputData($request, $this->user);
            $input['coupon_id'] = empty($input['coupon_id']) ?
                (isset($coupon) ? $coupon->id : null) :
                $input['coupon_id'];
            
            $model = new Order($input);
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            list($subtotal, $total) = $this->orderService->getTotals($model);
            
            $model->subtotal = $subtotal;
            $model->total = $total;
            
            $model->save();
            
            DB::commit();
            
            event(new NewOrder($model));
            
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
        
        $weekly_menu = $order->main_basket->weekly_menu_basket->weekly_menu;
        
        $this->data('weekly_menu', $weekly_menu);
        $this->data('data_tab', 'my-orders-edit');
        $this->data('profile_css_class', 'profile-orders order-edit');
        
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
        
        try {
            $status = $this->_validCoupon($request, $model);
    
            if ($status !== true) {
                return [
                    'status'  => 'error',
                    'message' => $status,
                ];
            }
            
            DB::beginTransaction();
            
            $input = $this->orderService->prepareEditFrontInputData($request);
            
            $model->fill($input);
            $model->save();
            
            $this->_saveEditRelationships($model, $request);
            
            list($subtotal, $total) = $this->orderService->getTotals($model);
            
            $model->subtotal = $subtotal;
            $model->total = $total;
            
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
     * @param int $order_id
     *
     * @return array
     */
    public function delete($order_id)
    {
        $model = $this->orderService->getOrder($order_id);
        
        try {
            if ($model->user_id != $this->user->id) {
                return [
                    'status'  => 'error',
                    'message' => trans('front_messages.order not found'),
                ];
            }
            
            if (!$model->isStatus('changed') && !$model->isStatus('tmpl')) {
                return [
                    'status'  => 'error',
                    'message' => trans('front_messages.you can not cancel payed or processed orders'),
                ];
            }
            
            $model->status = Order::getStatusIdByName('deleted');
            $model->save();
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.order successfully canceled'),
            ];
        } catch (Exception $e) {
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
        $additional_baskets = Basket::with('recipes', 'tags', 'tags.tag.category')
            ->additional()
            ->positionSorted()
            ->get();
        $this->data('additional_baskets', $additional_baskets);
        
        $additional_baskets_tags = [];
        foreach ($additional_baskets as $additional_basket) {
            foreach ($additional_basket->tags as $tag) {
                if ($tag->tag->category && $tag->tag->category->status) {
                    if (!isset($additional_baskets_tags[$tag->tag->id])) {
                        $additional_baskets_tags[$tag->tag->id] = [
                            'tag'   => $tag->tag,
                            'name'  => $tag->tag->name,
                            'price' => $additional_basket->price,
                        ];
                    }
                    
                    $additional_baskets_tags[$tag->tag->id]['price'] = min(
                        $additional_baskets_tags[$tag->tag->id]['price'],
                        $additional_basket->price
                    );
                }
            }
        }
        $this->data('additional_baskets_tags', collect($additional_baskets_tags)->sortBy('name'));
        
        $this->data('delivery_dates', $this->weeklyMenuService->getDeliveryDates($year, $week));
        
        $this->data('delivery_times', config('order.delivery_times'));
        
        $payment_methods = [];
        foreach (Order::getPaymentMethods() as $id => $payment_method) {
            $payment_methods[$id] = trans('front_labels.payment_method_'.$payment_method);
        }
        $this->data('payment_methods', $payment_methods);
        
        $subscribe_periods = [];
        foreach (BasketSubscribe::getSubscribePeriods() as $subscribe_period) {
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
        $basket_id = $request->get('basket_id');
        
        $this->orderService->saveRecipes($model, $basket_id, $request->get('recipes'));
        
        $this->orderService->saveMainBasket($model, $basket_id, $model->recipes->count());
        
        $this->orderService->saveAdditionalBaskets($model, $request->get('baskets', []));
        
        $this->orderService->saveIngredients($model, $basket_id, $request->get('ingredients', []));
        
        $this->subscribeService->store($this->user, $request);
        
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
            
            $old_recipes = $model->recipes()->with('recipe')->get();
            $model->recipes()->delete();
            
            $this->orderService->saveRecipes($model, $request->get('basket_id'), [], $old_recipes);
        }
        
        $this->orderService->saveMainBasket($model, $request->get('basket_id'), $model->recipes->count());
        
        $this->orderService->saveAdditionalBaskets($model, $request->get('baskets', []));
        
        $this->subscribeService->store($this->user, $request);
        
        $this->orderService->addSystemOrderComment($model, trans('front_messages.user change the order'));
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order|null   $order
     *
     * @return bool
     */
    private function _validCoupon(Request $request, $order = null)
    {
        if ($request->get('coupon_code')) {
            $status = $this->couponService->available($request->get('coupon_code'), $this->user);
            
            if ($status !== true) {
                return $status;
            }
            
            $this->couponService->saveUserCoupon($this->user, $request->get('coupon_code'));
        } else {
            $coupon_id = $request->get('coupon_id');
            
            if ($coupon_id && (!$order || $coupon_id != $order->coupon_id)) {
                $status = $this->couponService->availableById($coupon_id, $this->user);
                
                if ($status !== true) {
                    return $status;
                }
            }
        }
        
        return true;
    }
}