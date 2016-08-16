<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 16.08.16
 * Time: 12:56
 */

namespace App\Services;

use App\Models\Order;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService
{
    
    /**
     * @param \App\Models\Order $order
     *
     * @return array|bool
     */
    public function automaticallyPay(Order $order)
    {
        if (!$order->canBePaidOnline()) {
            return [
                'status'  => 'warning',
                'message' => trans('messages.order no online payment').': '.
                    trans('labels.payment_method_'.$order->getStringPaymentMethod()),
            ];
        }
        
        //TODO: implement online payment
        return true;
    }
}