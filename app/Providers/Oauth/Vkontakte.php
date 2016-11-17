<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 16.11.16
 * Time: 10:50
 */

namespace App\Providers\Oauth;

use App\Contracts\SocialProviderContract;
use Carbon\Carbon;
use OAuth;

/**
 * Class Vkontakte
 * @package App\Providers\Oauth
 */
class Vkontakte implements SocialProviderContract
{
    
    /**
     * @var string
     */
    protected $provider = 'Vkontakte';
    
    /**
     * @var
     */
    protected $consumer;
    
    /**
     * Vkontakte constructor.
     */
    public function __construct()
    {
        $this->consumer = OAuth::consumer($this->provider);
    }
    
    /**
     * @return string
     */
    public function loginUrl()
    {
        return $this->consumer->getAuthorizationUri()->getAbsoluteUri();
    }
    
    /**
     * @param string $code
     *
     * @return array
     */
    public function profile($code)
    {
        $token = $this->consumer->requestAccessToken($code);
        
        $extraParams = $token->getExtraParams();
        
        $user_id = $extraParams['user_id'];
        
        $result = json_decode(
            $this->consumer->request("users.get?uids={$user_id}&fields=bdate,domain,sex,photo_max_orig,city,contacts"),
            true
        );
        
        if (!isset($result['response'][0]) || empty($extraParams['email'])) {
            return [];
        };
        
        $result = $result['response'][0];
        
        return [
            'id'               => $result['uid'],
            'email'            => $extraParams['email'],
            'full_name'        => trim($result['last_name'].' '.$result['first_name']),
            'gender'           => isset($result['sex']) && $result['sex'] == 1 ? 'female' : 'male',
            'birthday'         => empty($result['bdate']) ?
                null :
                Carbon::createFromFormat('d.m.Y', $result['bdate'])->format('d-m-Y'),
            'token'            => $token->getAccessToken(),
            'city'             => empty($result['city']) ? null : $result['city']['title'],
            'avatar'           => empty($result['photo_max_orig']) ? null : $result['photo_max_orig'],
            'url'              => 'https://vk.com/'.$result['domain'],
            'provider'         => $this->provider,
            'phone'            => !empty($result['contacts']['phone']) ? $result['contacts']['phone'] : null,
            'additional_phone' => !empty($result['contacts']['home_phone']) ? $result['contacts']['home_phone'] : null,
        ];
    }
}