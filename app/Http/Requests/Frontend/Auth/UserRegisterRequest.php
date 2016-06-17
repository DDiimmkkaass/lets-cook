<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\FormRequest;
use App\Models\UserInfo;

/**
 * Class UserRegisterRequest
 * @package App\Http\Requests\Frontend\Auth
 */
class UserRegisterRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users',
            'phone'                 => 'string|regex:/^\+[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'password'              => 'required|confirmed:password_confirmation|min:' .
                config('auth.passwords.min_length'),
            'password_confirmation' => 'required',
        ];
        
        return $rules;
    }
}