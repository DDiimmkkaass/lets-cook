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
            'email'            => 'required|email|unique:users,email,'.Sentry::getUser()->getId().',id',
            'full_name'        => 'required',
            'birthday'         => 'date_format:d-m-Y',
            'phone'            => 'required|string|regex:/^\+[0-9]+$/|max:17|min:'.config('user.min_phone_length'),
            'additional_phone' => 'string|regex:/^\+[0-9]+$/|max:17|min:'.config('user.min_phone_length'),
            'gender'           => 'required|in:'.implode(',', UserInfo::$genders),
            'city_id'          => 'required_without:city_name|exists:cities,id',
            'city_name'        => 'required_without:city_id',
            'address'          => 'required',
        ];
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'city_id.required_without' => trans('validation.city_id required without city name'),
            'city_name.required'       => trans('validation.city name is required'),
            'phone.regex'              => trans('validation.user phone validation error'),
            'additional_phone.regex'   => trans('validation.user additional phone validation error'),
        ];
    }
}