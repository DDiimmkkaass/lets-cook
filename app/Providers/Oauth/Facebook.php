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
 * Class Facebook
 * @package App\Providers\Oauth
 */
class Facebook implements SocialProviderContract
{
    
    /**
     * @var string
     */
    protected $provider = 'Facebook';
    
    protected $consumer;
    
    /**
     * Facebook constructor.
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
        
        $result = json_decode($this->consumer->request('/me'), true);
        
        if (empty($result)) {
            return [];
        }
        
        $result = json_decode(
            $this->consumer->request(
                '/'.$result['id'].'?fields=id,email,name,last_name,first_name,middle_name,gender,birthday,picture.type(large),link'
            ),
            true
        );
        
        return [
            'id'               => $result['id'],
            'email'            => !empty($result['email']) ? $result['email'] : null,
            'full_name'        => empty($result['name']) ?
                trim($result['last_name'].' '.$result['first_name'].' '.$result['middle_name']) :
                $result['name'],
            'gender'           => $result['gender'],
            'birthday'         => empty($result['birthday']) ?
                null :
                Carbon::createFromFormat('m/d/Y', $result['birthday'])->format('d-m-Y'),
            'token'            => $token->getAccessToken(),
            'city'             => null,
            'avatar'           => isset($result['picture']['data']['url']) ? $result['picture']['data']['url'] : null,
            'url'              => empty($result['link']) ? null : $result['link'],
            'provider'         => $this->provider,
            'phone'            => null,
            'additional_phone' => null,
        ];
    }
}