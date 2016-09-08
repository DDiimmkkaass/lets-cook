<?php

namespace App\Http\Requests\Frontend\Order;

use App\Http\Requests\FormRequest;
use App\Models\Order;
use Sentry;

/**
 * Class OrderCreateRequest
 * @package App\Http\Requests\Frontend\Order
 */
class OrderCreateRequest extends FormRequest
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
            'basket_id'   => 'required|exists:weekly_menu_baskets,id',
            'verify_call' => 'boolean',
            
            'delivery_date' => 'required|date_format:"d-m-Y"|delivery_date_day_of_week|delivery_date_date|max_delivery_date_date',
            'delivery_time' => 'required|in:'.implode(',', config('order.delivery_times')),
            
            'city_id'   => 'required_without:city_name|exists:cities,id',
            'city_name' => 'required_without:city_id',
            'address'   => 'required',
            
            'subscribe_period' => 'numeric|min:1|required_if:type,'.Order::getTypeIdByName('subscribe'),
            'payment_method'   => 'required|in:'.implode(',', array_keys(Order::getPaymentMethods())),
            
            'baskets'     => 'array',
            'ingredients' => 'array',
            
            'baskets.*' => 'exists:baskets,id',
            
            'terms' => 'accepted',
        ];
        
        if (!$user_id) {
            $rules['full_name'] = 'required';
            $rules['email'] = 'required|email';
            $rules['phone'] = 'required';
        }
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'terms.accepted' => trans('validation.terms accepted'),
        ];
    }
}
