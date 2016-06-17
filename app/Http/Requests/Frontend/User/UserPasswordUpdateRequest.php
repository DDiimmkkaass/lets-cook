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
 * Class UserPasswordUpdateRequest
 * @package App\Http\Requests\Frontend\User
 */
class UserPasswordUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|confirmed:password_confirmation|min:'.
                config('auth.passwords.min_length'),
            'password_confirmation' => 'required',
        ];

        return $rules;
    }
}