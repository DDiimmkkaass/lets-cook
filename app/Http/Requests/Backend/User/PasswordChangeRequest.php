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
 * Class PasswordChangeRequest
 * @package App\Http\Requests\Backend\User
 */
class PasswordChangeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'  => 'required|confirmed:password_confirmation|min:'.config('auth.passwords.min_length'),
            'password_confirmation' => 'required'
        ];
    }
}
