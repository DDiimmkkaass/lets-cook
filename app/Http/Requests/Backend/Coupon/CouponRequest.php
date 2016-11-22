<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Coupon;

use App\Http\Requests\FormRequest;
use App\Models\Coupon;

/**
 * Class CouponRequest
 * @package App\Http\Requests\Backend\Coupon
 */
class CouponRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $discount_type = $this->request->get('discount_type', 0);
        
        $rules = [
            'type'          => 'required|in:'.implode(',', array_keys(Coupon::getTypes())),
            'name'          => 'required',
            'discount'      => 'required|min:0',
            'discount_type' => 'required|in:'.implode(',', array_keys(Coupon::getDiscountTypes())),
            'count'         => 'required|integer|min:0',
            'users_count'   => 'required|integer|min:0',
            'users_type'    => 'required|in:'.implode(',', array_keys(Coupon::getUsersTypes())),
            'started_at'    => 'date_format:d-m-Y',
            'expired_at'    => 'date_format:d-m-Y',
        ];
        
        $discount_types = Coupon::getDiscountTypes();
        
        if ($discount_types[$discount_type] == 'percentage') {
            $rules['discount'] .= '|integer|max:100';
        } else {
            $rules['discount'] .= '|numeric';
        }
    
        $coupon = $this->route('coupon', null);
        if (!$coupon) {
            $rules['create_count'] = 'required_without:codes|integer';
            $rules['codes'] = 'required_without:create_count';
        }
        
        return $rules;
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'started_at.date_format' => trans('validation.coupon started_at date_format error'),
        ];
    }
}