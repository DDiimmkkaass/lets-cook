<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.09.16
 * Time: 15:31
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\Order\OrderCreateRequest;
use App\Models\Basket;
use App\Models\City;
use App\Models\Order;
use App\Models\WeeklyMenuBasket;
use App\Services\OrderService;
use Carbon\Carbon;
use DB;
use Exception;
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
     * OrderController constructor.
     *
     * @param \App\Services\OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        
        $this->orderService = $orderService;
    }
    
    /**
     * @param int $basket_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index($basket_id)
    {
        $now = Carbon::now();
        
        $basket = WeeklyMenuBasket::with(
            [
                'main_recipes',
                'main_recipes.recipe.ingredients',
                'main_recipes.recipe.home_ingredients',
            ]
        )->JoinWeeklyMenu()
            ->where('weekly_menus.year', $now->year)
            ->where('weekly_menus.week', $now->weekOfYear)
            ->select('weekly_menu_baskets.*')
            ->find($basket_id);
        
        abort_if(!$basket, 404);
        
        $this->data('basket', $basket);
        
        $this->_fillAdditionalTemplateData();
        
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
            
            $input = $this->orderService->prepareFrontInputData($request, $this->user);
            
            abort_if(!$this->orderService->checkCurrentWeekBasket($input['basket_id']), 404);
            
            $model = new Order($input);
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            $model->total = $model->getTotal();
            
            $model->save();
            
            DB::commit();
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.your order successfully created'),
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
     */
    private function _fillAdditionalTemplateData()
    {
        $now = Carbon::now();
        
        $stop_day = variable('stop_ordering_date');
        $stop_time = variable('stop_ordering_time');
        
        $additional_baskets = Basket::with('recipes')->additional()->positionSorted()->get();
        $this->data('additional_baskets', $additional_baskets);
        
        $delivery_dates = [];
        if ($now->dayOfWeek > $stop_day || ($now->dayOfWeek == $stop_day && $now->format('H:i') >= $stop_time)) {
            $now->addWeek();
        }
        $delivery_dates[] = clone ($now->endOfWeek()->startOfDay());
        $delivery_dates[] = clone ($now->endOfWeek()->addDay()->endOfDay());
        $delivery_dates[] = clone ($now->endOfWeek()->startOfDay());
        $delivery_dates[] = clone ($now->endOfWeek()->addDay()->endOfDay());
        $this->data('delivery_dates', $delivery_dates);
        
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
        
        $this->orderService->saveAdditionalBaskets($model, $request->get('baskets', []));
        
        $this->orderService->saveIngredients($model, $request->get('ingredients', []));
        
        $this->orderService->addSystemOrderComment($model, trans('front_messages.new order'));
    }
}