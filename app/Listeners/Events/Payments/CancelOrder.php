<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.09.16
 * Time: 15:04
 */

namespace App\Listeners\Events\Payments;

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
     * cancelOrder constructor.
     *
     * @param \App\Services\OrderService $orderService
     * @param \App\Services\PaymentService $paymentService
     */
    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    /**
     * @param \Artem328\LaravelYandexKassa\Events\BeforeCancelOrderResponse
     *
     * @return array|null
     */
    public function handle(BeforeCancelOrderResponse $event)
    {
        $this->paymentService->storeTransaction(
            $event->request->get('orderNumber'),
            $event->request->all(),
            'error',
            'checkPayment'
        );
        
        return null;
    }
}