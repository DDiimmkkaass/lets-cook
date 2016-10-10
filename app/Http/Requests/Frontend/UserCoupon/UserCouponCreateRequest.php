<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\UserCoupon;

use App\Http\Requests\FormRequest;

/**
 * Class UserCouponCreateRequest
 * @package App\Http\Requests\Frontend\UserCoupon
 */
class UserCouponCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required|exists:coupons,code',
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