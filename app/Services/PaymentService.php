<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 16.08.16
 * Time: 12:56
 */

namespace App\Services;

use App\Contracts\PaymentProvider;
use App\Exceptions\UnsupportedPaymentMethod;
use App\Exceptions\UnsupportedPaymentProvider;
use App\Models\Card;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Exception;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService
{
    
    /**
     * @param string $key
     *
     * @return PaymentProvider
     * @throws \App\Exceptions\UnsupportedPaymentProvider
     */
    public function getProvider($key = 'yandex_kassa')
    {
        $class = '\App\Providers\Payment\\'.(studly_case($key));
        
        if (class_exists($class)) {
            return new $class;
        }
        
        throw new UnsupportedPaymentProvider($key);
    }
    
    /**
     * @param \App\Models\Order  $order
     * @param \App\Models\Card $card
     *
     * @return array|bool
     * @throws \App\Exceptions\UnsupportedPaymentMethod
     */
    public function automaticallyPay(Order $order, Card $card)
    {
        if (!$order->canBePaidOnline()) {
            throw new UnsupportedPaymentMethod(trans('messages.order no online payment').': '.
                trans('labels.payment_method_'.$order->getStringPaymentMethod()));
        }
        
        $provider = $this->getProvider();
    
        $provider->pay($order, $card);
        
        return true;
    }
    
    /**
     * @param int    $order_id
     * @param array  $data
     * @param string $status
     * @param string $description
     */
    public function storeTransaction($order_id, $data, $status = 'info', $description = '')
    {
        PaymentTransaction::create(
            [
                'order_id'    => $order_id,
                'amount'      => isset($data['orderSumAmount']) ? $data['orderSumAmount'] : 0,
                'currency'    => isset($data['orderSumCurrencyPaycash']) ? $data['orderSumCurrencyPaycash'] : null,
                'status'      => $status,
                'description' => $description,
                'data'        => serialize($data),
            ]
        );
    }
}