<?php

namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\BeforeErrorDepositionNotificationResponse;
use Artem328\LaravelYandexKassa\Requests\YandexKassaRequest;
use Artem328\LaravelYandexKassa\YandexKassaController;
use FlashMessages;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Frontend
 */
class PaymentController extends YandexKassaController
{
    
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success()
    {
        return redirect()->route('order.success');
    }
    
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fail()
    {
        session()->forget('success_order');
        
        FlashMessages::add('error', trans('Платеж завершился ошибкой, заказ не оплачен.'));
        
        return redirect()->route('home');
    }
    
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function instruction()
    {
        return redirect()->route('home');
    }
    
    /**
     * we do not use this method makeDeposition, so we can not handle this request
     * just in case, send 200 response
     */
    public function depositError()
    {
        return;
    }
}
