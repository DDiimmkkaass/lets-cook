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
     * @var array
     */
    protected $dontFlash = ['imageUpload'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required',
            'birthday' => 'date_format:d-m-Y',
            'phone'    => 'string|regex:/^\+[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'gender'   => 'in:' . implode(',', UserInfo::$genders)
        ];
    }
}
