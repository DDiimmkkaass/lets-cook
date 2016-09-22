<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:01
 */

namespace App\Services;

use App\Events\Frontend\UserQuickRegister;
use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
use App\Models\UserInfo;
use Cartalyst\Sentry\Users\UserInterface;
use Request;
use Sentry;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    
    /**
     * @param array $credentials
     *
     * @return bool|UserInterface
     */
    public function login($credentials = [])
    {
        if (empty($credentials)) {
            return false;
        }
        
        $user = Sentry::authenticate($credentials, true);
        if (!$user) {
            return false;
        }
        
        $throttle = Sentry::findThrottlerByUserId($user->id);
        if ($throttle->isSuspended() || $throttle->isBanned()) {
            Sentry::logout();
            
            return false;
        }
        
        return $user;
    }
    
    /**
     * @param array $input
     *
     * @return UserInterface
     */
    public function register($input)
    {
        $user = Sentry::getUserProvider()->create($input);
        
        $this->saveUserInfo($user, $input);
        
        return $user;
    }
    
    /**
     * @param array $data
     *
     * @return \App\Models\User|\Cartalyst\Sentry\Users\UserInterface
     */
    public function quickRegister($data)
    {
        $input = [
            'email'     => $data['email'],
            'phone'     => $data['phone'],
            'full_name' => $data['full_name'],
            'password'  => str_random(config('auth.passwords.min_length')),
        ];
        
        $user = $this->register($input);
    
        event(new UserQuickRegister($user, $input));
        
        $user->activated = true;
        $user->save();
        
        Sentry::login($user);
        
        return $user;
    }
    
    /**
     * @param UserRegisterRequest $request
     *
     * @return array
     */
    public function prepareRegisterInput(UserRegisterRequest $request)
    {
        $input = $request->only(['full_name', 'email', 'phone', 'additional_phone', 'gender', 'birthday', 'password']);
        
        $input['activated'] = false;
        $input['ip_address'] = !empty($input['ip_address']) ? $input['ip_address'] : Request::getClientIp();
        
        return $input;
    }
    
    /**
     * @param \App\Models\User|\Cartalyst\Sentry\Users\UserInterface $user
     * @param array                                                  $input
     */
    private function saveUserInfo($user, $input)
    {
        $user_info = new UserInfo($input);
        
        $user->info()->save($user_info);
    }
}