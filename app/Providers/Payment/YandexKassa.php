<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.09.16
 * Time: 13:25
 */

namespace App\Providers\Payment;

use App\Contracts\PaymentProvider;
use App\Exceptions\AutomaticallyPayException;
use App\Models\Card;
use App\Models\Order;
use App\Models\User;
use Exception;
use Guzzle;

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
     * @param \App\Models\Card  $card
     *
     * @return bool
     * @throws \Exception
     */
    public function pay(Order $order, Card $card)
    {
        $result = $this->_pay($order, $card);
        
        if ($result) {
            $this->_confirm($result, $order);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * @param \App\Models\Order $order
     * @param \App\Models\Card  $card
     *
     * @return array|bool
     */
    public function _pay(Order $order, Card $card)
    {
        $_data =
            [
                'clientOrderId' => $order->id,
                'invoiceId'     => $card->invoice_id,
                'amount'        => $order->total,
                'orderNumber'   => $order->id,
            ];
        
        $response = $this->_sendRequest($this->_getPayUrl(), $_data);
        
        $response = $response['@attributes'];
        
        return $this->_checkRequest($response) ? $response : false;
    }
    
    /**
     * @param array             $response
     * @param \App\Models\Order $order
     *
     * @return bool
     */
    public function _confirm($response, Order $order)
    {
        $data = [
            'requestDT' => $response['processedDT'],
            'orderId'   => $response['clientOrderId'],
            'amount'    => $order->total,
            'currency'  => config('yandex_kassa.currency'),
        ];
        
        $response = $this->_sendRequest($this->_getConfirmUrl(), $data);
        
        $response = $response['@attributes'];
        
        return $this->_checkRequest($response);
    }
    
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
     * @return string
     */
    public function getConnectForm($data)
    {
        return view('vendor.yandex_kassa.connect_form', ['order' => $data])->render();
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
     * @param array $data
     *
     * @return bool
     */
    public function validCardConnectPayment($data)
    {
        $this->errors = [];
        
        if (!isset($data['orderNumber'])) {
            $this->errors[] = trans('payments.empty card number');
        } else {
            if (empty($data['rebillingOn'])) {
                $this->errors[] = trans('payments.user is not granted access');
            } else {
                $card_id = $data['orderNumber'];
                $card_id = explode('_', $card_id);
                
                if (!isset($card_id[1])) {
                    $this->errors[] = trans('payments.wrong card number');
                } else {
                    $card = Card::find($card_id[1]);
                    
                    if (!$card) {
                        $this->errors[] = trans('payments.card not find');
                    } else {
                        if ($card->invoice_id) {
                            $this->errors[] = trans('payments.card already connected');
                        }
                        
                        if ($data['orderSumAmount'] != 1) {
                            $this->errors[] = trans('payments.wrong payment amount');
                        }
                        
                        if ($data['customerNumber'] != $card->user_id) {
                            $this->errors[] = trans('payments.wrong user id');
                        }
                        
                        if (!User::active()->whereId($data['customerNumber'])->count()) {
                            $this->errors[] = trans('payments.user not find');
                        }
                    }
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
    
    /**
     * @return string
     */
    private function _getPayUrl()
    {
        return config('yandex_kassa.test_mode') ?
            config('yandex_kassa.mws.pay.test_mode_url') :
            config('yandex_kassa.mws.pay.url');
    }
    
    /**
     * @return string
     */
    private function _getConfirmUrl()
    {
        return config('yandex_kassa.test_mode') ?
            config('yandex_kassa.mws.confirm.test_mode_url') :
            config('yandex_kassa.mws.confirm.url');
    }
    
    /**
     * @param string $url
     * @param array  $data
     *
     * @return array
     */
    private function _sendRequest($url, $data)
    {
        $response = Guzzle::request(
            'POST',
            $url,
            [
                'form_params' => $data,
                'curl'        => [
                    CURLOPT_SSLCERT        => config('yandex_kassa.mws.cert'),
                    CURLOPT_SSLKEY         => config('yandex_kassa.mws.private_key'),
                    CURLOPT_SSLKEYPASSWD   => config('yandex_kassa.mws.cert_password'),
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ],
            ]
        );
        
        return (array) simplexml_load_string((string) $response->getBody());
    }
    
    /**
     * @param array $response
     *
     * @return bool
     * @throws \App\Exceptions\AutomaticallyPayException
     * @throws \Exception
     */
    private function _checkRequest($response)
    {
        $status = true;
        
        if (isset($response['status'])) {
            switch ((int) $response['status']) {
                case 0:
                    // all good, move on
                    break;
                case 1:
                    $status = false;
                    // payment server return status = 'processed', repeat request one more time
                    // repeat not needed if this is a payment confirm
                    break;
                case 3:
                    throw new AutomaticallyPayException('Payment error : '.$this->__toStringError($response));
                    break;
            }
        } else {
            throw new Exception('Unacceptable response: '.$this->__toStringError($response));
        }
        
        return $status;
    }
    
    /**
     * @param array $data
     *
     * @return string
     */
    private function __toStringError($data)
    {
        $error = '';
        
        foreach ($data as $key => $value) {
            $error .= $key.': '.$value.', ';
        }
        
        return trim($error, ', ');
    }
}