<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Exceptions;

use Exception;

/**
 * Class UnsupportedPaymentProvider
 * @package App\Exceptions
 */
class UnsupportedPaymentProvider extends Exception
{
    
    /**
     * UnsupportedPaymentProvider constructor.
     *
     * @param string $provider
     */
    public function __construct($provider = '')
    {
        parent::__construct(trans('messages.unsupported payment provider: :provider', ['provider' => $provider]));
    }
}