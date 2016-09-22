<?php

namespace App\Http\Controllers\Frontend;

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
        FlashMessages::add('success', trans('Спасибо! Ваш платеж успешно зачислен.'));
        
        return redirect()->route('home');
    }
    
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fail()
    {
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
}
