<?php

namespace App\Http\Controllers\Frontend;

use App\Services\PaymentService;
use FlashMessages;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Frontend
 */
class PaymentController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'payment';
    
    /**
     * @var \App\Services\PaymentService
     */
    protected $paymentService;
    
    /**
     * PaymentController constructor.
     *
     * @param \App\Services\PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        
        $this->paymentService = $paymentService;
    }
    
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
