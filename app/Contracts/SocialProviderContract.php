<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 16.11.16
 * Time: 11:34
 */

namespace App\Contracts;

/**
 * Class Facebook
 * @package App\Providers\Oauth
 */
interface SocialProviderContract
{
    /**
     * @return string
     */
    public function loginUrl();
    
    /**
     * @param string $code
     *
     * @return array
     */
    public function profile($code);
}