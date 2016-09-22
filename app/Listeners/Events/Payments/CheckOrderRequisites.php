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
use Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse;

/**
 * Class CheckOrderRequisites
 * @package App\Listeners\Events\Payments
 */
class CheckOrderRequisites
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
     * CheckOrderRequisites constructor.
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
     * @param \Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse
     *
     * @return array|null
     */
    public function handle(BeforeCheckOrderResponse $event)
    {
        $provider = $this->paymentService->getProvider();
        
        if (!$provider->validPayment($event->request->all())) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = implode(', ', $provider->getErrors());
            $event->responseParameters['techMessage'] = trans('payments.validation_fails');
            
            $order = Order::find($event->request->get('orderNumber'));
            
            if ($order) {
                $this->paymentService->storeTransaction($order->id, $event->request->all(), 'error', 'checkPayment');
                
                $this->orderService->addSystemOrderComment(
                    $order,
                    trans('payments.validation_fails').': '.implode(', ', $provider->getErrors())
                );
            }
            
            return $event->responseParameters;
        }
        
        return null;
    }
}