<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.09.16
 * Time: 16:54
 */

namespace App\Services;

use App\Models\BasketSubscribe;
use App\Models\Order;
use App\Models\User;
use App\Models\WeeklyMenuBasket;
use Carbon;
use Illuminate\Http\Request;

/**
 * Class SubscribeService
 * @package App\Services
 */
class SubscribeService
{
    
    /**
     * @param \App\Models\User         $user
     * @param \Illuminate\Http\Request $request
     */
    public function store(User $user, Request $request)
    {
        if (!$user->subscribe()->count() && ((int) $request->get('subscribe_period', 0) > 0)) {
            $basket = $this->_getBasketId($request);
            
            $subscribe = BasketSubscribe::create(
                array (
                    'user_id'          => $user->id,
                    'basket_id'        => $basket->basket_id,
                    'subscribe_period' => $request->get('subscribe_period'),
                    'delivery_date'    => $this->_getDeliveryDate($request),
                    'delivery_time'    => $request->get('delivery_time'),
                    'payment_method'   => $request->get('payment_method'),
                    'portions'         => $basket->portions,
                    'recipes'          => empty($request->get('recipes', [])) ?
                        $basket->recipes->count() :
                        count($request->get('recipes', [])),
                )
            );
            
            $this->_saveAdditionalBaskets($subscribe, $request);
            
            $this->generateTmplOrders($subscribe);
        }
    }
    
    /**
     * @param \App\Models\BasketSubscribe $subscribe
     */
    public function generateTmplOrders(BasketSubscribe $subscribe)
    {
        $all = false;
        $check_date = Carbon::now()->addWeeks(config('order.subscribe_auto_generation_time'));
        
        $last_tmpl_order = Order::ofStatus('tmpl')
            ->whereUserId($subscribe->user_id)
            ->orderBy('delivery_date', 'DESC')
            ->first();
        
        if ($last_tmpl_order) {
            $all = $last_tmpl_order->getDeliveryDate() >= $check_date;
        }
        
        if (!$all) {
            $orderService = new OrderService();
        }
        
        while (!$all) {
            $tmpl_order = $orderService->createTmpl($subscribe);
            
            $all = $tmpl_order->getDeliveryDate() >= $check_date;
        }
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    private function _getBasketId(Request $request)
    {
        $basket = WeeklyMenuBasket::with('recipes')->whereId($request->get('basket_id'))->first();
        
        return $basket;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    private function _getDeliveryDate(Request $request)
    {
        $delivery_date = Carbon::createFromFormat('d-m-Y', $request->get('delivery_date'));
        
        return $delivery_date->dayOfWeek;
    }
    
    /**
     * @param \App\Models\BasketSubscribe $subscribe
     * @param \Illuminate\Http\Request    $request
     */
    private function _saveAdditionalBaskets(BasketSubscribe $subscribe, Request $request)
    {
        $subscribe->additional_baskets()->sync($request->get('baskets', []));
    }
}