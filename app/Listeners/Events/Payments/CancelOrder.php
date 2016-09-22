<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.09.16
 * Time: 15:04
 */

namespace App\Listeners\Events\Payments;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Artem328\LaravelYandexKassa\Events\BeforeCancelOrderResponse;

/**
 * Class CancelOrder
 * @package App\Listeners\Events\Payments
 */
class CancelOrder
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
     * cancelOrder constructor.
     *
     * @param \App\Services\OrderService $orderService
     * @param \App\Services\PaymentService $paymentService
     */
    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }
    
    /**
     * @param \Artem328\LaravelYandexKassa\Events\BeforeCancelOrderResponse
     *
     * @return array|null
     */
    public function handle(BeforeCancelOrderResponse $event)
    {
        $order = Order::find($event->request->get('orderNumber'));
        
        if ($order) {
            $order->status = Order::getStatusIdByName('changed');
            $order->save();
    
            $this->orderService->addSystemOrderComment($order, trans('payments.cancel order'));
        }
        
        $this->paymentService->storeTransaction(
            $event->request->get('orderNumber'),
            $event->request->all(),
            'error',
            'cancelOrder'
        );
        
        return null;
    }
}