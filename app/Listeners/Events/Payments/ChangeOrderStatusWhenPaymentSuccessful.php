<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.09.16
 * Time: 15:05
 */

namespace App\Listeners\Events\Payments;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse;
use Carbon\Carbon;

/**
 * Class ChangeOrderStatusWhenPaymentSuccessful
 * @package App\Listeners\Events\Payments
 */
class ChangeOrderStatusWhenPaymentSuccessful
{
    
    /**
     * @var \App\Services\PaymentService
     */
    private $paymentService;
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * ChangeOrderStatusWhenPaymentSuccessful constructor.
     *
     * @param \App\Services\PaymentService $paymentService
     * @param \App\Services\OrderService   $orderService
     */
    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }
    
    /**
     * @param \Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse
     *
     * @return void
     */
    public function handle(BeforePaymentAvisoResponse $event)
    {
        $order = Order::find($event->request->get('orderNumber'));
        
        if ($event->request->isValidHash()) {
            $order->status = $this->_getStatus($order);
            $order->save();
            
            $this->orderService->addSystemOrderComment($order, trans('payments.success_payment'));
            
            $this->paymentService->storeTransaction($order->id, $event->request->all(), 'success');
        } else {
            $this->orderService->addSystemOrderComment($order, trans('payments.invalid_has'));
            
            $this->paymentService->storeTransaction($order->id, $event->request->all(), 'error');
        }
    }
    
    /**
     * @param \App\Models\Order $order
     *
     * @return int
     */
    private function _getStatus(Order $order)
    {
        if ($order->forCurrentWeek()) {
            $now = active_week();
    
            if (after_week_closing($now->year, $now->weekOfYear) && before_finalisation($now->year, $now->weekOfYear)) {
                return Order::getStatusIdByName('processed');
            }
        }
        
        return Order::getStatusIdByName('paid');
    }
}