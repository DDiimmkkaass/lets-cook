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
            'email'            => 'required|email|unique:users',
            'full_name'        => 'required',
            'phone'            => 'required|string|max:17|min:'.config('user.min_phone_length'),
            'additional_phone' => 'string|max:17|min:'.config('user.min_phone_length'),
            'gender'           => 'in:'.implode(',', UserInfo::$genders),
            'birthday'         => 'date_format:d-m-Y',
            'password'         => 'required|min:'.config('auth.passwords.min_length'),
        ];
        
        return $rules;
    }
}