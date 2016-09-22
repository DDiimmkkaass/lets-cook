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
     * @return void
     */
    public function handle(BeforePaymentAvisoResponse $event)
    {
        $order = Order::find($event->request->get('orderNumber'));
        
        if ($event->request->isValidHash()) {
            $order->status(Order::getStatusIdByName('paid'));
            $order->save();
    
            $this->orderService->addSystemOrderComment($order, trans('payments.success_payment'));
            
            $this->paymentService->storeTransaction($order->id, $event->request->all(), 'success');
        } else {
            $this->orderService->addSystemOrderComment($order, trans('payments.invalid_has'));
    
            $this->paymentService->storeTransaction($order->id, $event->request->all(), 'error');
        }
    }
}