<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\FormRequest;
use App\Models\UserInfo;
use Sentry;

/**
 * Class UserUpdateRequest
 * @package App\Http\Requests\Frontend\User
 */
class UserUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'  => 'required|email|unique:users,email,' . Sentry::getUser()->getId() . ',id',
            'phone'  => 'string|regex:/^\+[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'name'   => 'required',
            'gender' => 'in:' . implode(',', UserInfo::$genders),
        ];

        return $rules;
    }
}