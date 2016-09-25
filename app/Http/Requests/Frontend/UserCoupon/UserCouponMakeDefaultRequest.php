<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\UserCoupon;

use App\Http\Requests\FormRequest;
use Sentry;

/**
 * Class UserCouponMakeDefaultRequest
 * @package App\Http\Requests\Frontend\UserCoupon
 */
class UserCouponMakeDefaultRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = Sentry::getUser()->getId();
        
        $rules = [
            'coupon_id'  => 'required|exists:user_coupons,coupon_id,user_id,'.$user_id,
        ];

        return $rules;
    }
}