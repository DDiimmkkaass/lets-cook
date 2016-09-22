<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.09.16
 * Time: 13:25
 */

namespace App\Providers\Payment;

use App\Contracts\PaymentProvider;
use App\Models\Order;
use App\Models\User;

/**
 * Class YandexKassa
 * @package App\Providers\Payment
 */
class YandexKassa implements PaymentProvider
{
    
    /**
     * @var array
     */
    protected $errors = [];
    
    /**
     * @param \App\Models\Order $order
     *
     * @return string
     */
    public function getForm(Order $order)
    {
        return view('vendor.yandex_kassa.form', ['order' => $order])->render();
    }
    
    /**
     * @param array $data
     *
     * @return bool
     */
    public function validPayment($data)
    {
        $this->errors = [];
        
        if (!isset($data['orderNumber'])) {
            $this->errors[] = trans('payments.empty order number');
        } else {
            $order = Order::find($data['orderNumber']);
            
            if (!$order) {
                $this->errors[] = trans('payments.order not find');
            } else {
                if ($order->getStringStatus() != 'changed') {
                    $this->errors[] = trans('payments.order status already changed');
                }
    
                if ($data['orderSumAmount'] != $order->total) {
                    $this->errors[] = trans('payments.wrong order total amount');
                }
    
                if ($data['customerNumber'] != $order->user_id) {
                    $this->errors[] = trans('payments.wrong user id');
                }
    
                if (!User::active()->whereId($data['customerNumber'])->count()) {
                    $this->errors[] = trans('payments.user not find');
                }
            }
        }
        
        return empty($this->errors) ? true : false;
    }
    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}