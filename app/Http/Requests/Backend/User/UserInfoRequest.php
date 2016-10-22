<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\User;

use App\Http\Requests\FormRequest;
use App\Models\UserInfo;

/**
 * Class UserInfoRequest
 * @package App\Http\Requests\Backend\User
 */
class UserInfoRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name'        => 'required',
            'birthday'         => 'date_format:d-m-Y',
            'phone'            => 'required|string|regex:/^[0-9]+$/|max:17|min:'.config('user.min_phone_length'),
            'additional_phone' => 'string|regex:/^[0-9]+$/|max:17|min:'.config('user.min_phone_length'),
            'gender'           => 'in:'.implode(',', UserInfo::$genders),
            'city_id'          => 'required_without:city_name|exists:cities,id',
            'city_name'        => 'required_without:city_id',
            'address'          => 'required',
        ];
    }
}
