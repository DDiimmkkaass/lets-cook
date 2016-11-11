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
 * Class CheckCardCreateRequisites
 * @package App\Listeners\Events\Payments
 */
class CheckCardCreateRequisites
{
    /**
     * @var \App\Services\PaymentService
     */
    private $paymentService;
    
    /**
     * CheckCardCreateRequisites constructor.
     *
     * @param \App\Services\PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    /**
     * @param \Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse
     *
     * @return array|null
     */
    public function handle(BeforeCheckOrderResponse $event)
    {
        if ($event->request->get('cardConnect')) {
            $provider = $this->paymentService->getProvider();
    
            if (!$provider->validCardConnectPayment($event->request->all())) {
                $event->responseParameters['code'] = 100;
                $event->responseParameters['message'] = implode(', ', $provider->getErrors());
                $event->responseParameters['techMessage'] = trans('payments.validation_fails');
                
                return $event->responseParameters;
            }
    
            return null;
        }
    }
}