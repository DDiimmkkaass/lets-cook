<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\User;

use App\Http\Requests\FormRequest;

/**
 * Class UserCreateRequest
 * @package App\Http\Requests\Backend\User
 */
class UserCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|confirmed:password_confirmation|min:'.config(
                    'auth.passwords.min_length'
                ),
            'password_confirmation' => 'required',
            'groups'                => 'array',
        ];

        return array_merge($rules, app('App\Http\Requests\Backend\User\UserInfoRequest')->rules());
    }
}
