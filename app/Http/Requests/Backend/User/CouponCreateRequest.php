<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Backend\User;

use App\Http\Requests\FormRequest;

/**
 * Class CouponCreateRequest
 * @package App\Http\Requests\Backend\User
 */
class CouponCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'code'    => 'required|exists:coupons,code',
        ];
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'code.exists' => trans('validation.coupon code validation error'),
        ];
    }
}