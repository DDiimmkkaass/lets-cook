<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.09.16
 * Time: 13:24
 */

namespace App\Contracts;

use App\Models\Card;
use App\Models\Order;

/**
 * Interface PaymentProvider
 * @package App\Contracts
 */
interface PaymentProvider
{
    
    /**
     * @param \App\Models\Order $order
     * @param \App\Models\Card  $card
     *
     * @return array|bool|\SimpleXMLElement
     */
    public function pay(Order $order, Card $card);
    
    /**
     * @param \App\Models\Order $order
     *
     * @return mixed
     */
    public function getForm(Order $order);
    
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function getConnectForm($data);
    
    /**
     * @param array $data
     *
     * @return bool
     */
    public function validPayment($data);
    
    /**
     * @param array $data
     *
     * @return bool
     */
    public function validCardConnectPayment($data);
    
    /**
     * @return array
     */
    public function getErrors();
}