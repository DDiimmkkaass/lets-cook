<?php

namespace App\Http\Requests\Frontend\Order;

use App\Http\Requests\FormRequest;
use App\Models\Basket;
use App\Models\Order;
use App\Models\WeeklyMenuBasket;
use App\Services\WeeklyMenuService;

/**
 * Class OrderCreateRequest
 * @package App\Http\Requests\Frontend\Order
 */
class OrderCreateRequest extends FormRequest
{
    /**
     * @var \App\Services\WeeklyMenuService
     */
    private $weeklyMenuService;
    
    /**
     * OrderCreateRequest constructor.
     *
     * @param \App\Services\WeeklyMenuService $weeklyMenuService
     */
    public function __construct(WeeklyMenuService $weeklyMenuService)
    {
        parent::__construct();
        
        $this->weeklyMenuService = $weeklyMenuService;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $weekly_menu = $this->weeklyMenuService->getWeeklyMenuByBasketId($this->request->get('basket_id', 0));
        $city_id = $this->request->get('city_id', '');
        
        $delivery_rules = ['required'];
        if ($this->request->get('basket_slug', '') != variable('new_year_basket_slug') || $weekly_menu->week != 52) {
            $delivery_rules = [
                'required',
                'date_format:"d-m-Y"',
                'delivery_date_day_of_week',
                'delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week,
                'max_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week,
                'min_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week,
            ];
        }
        
        $rules = [
            'basket_id'   => 'required|exists:weekly_menu_baskets,id',
            'verify_call' => 'boolean',
            
            'coupon_code' => 'exists:coupons,code',
            
            'full_name' => 'required',
            'email'     => 'required|email',
            'phone'     => 'required|string|regex:/^\+[0-9]+$/|max:17|min:'.config('user.min_phone_length'),
            
            'delivery_date' => $delivery_rules,
            'delivery_time' => 'required|in:'.implode(',', config('order.delivery_times')),
            
            'city_id'   => 'required_without:city_name'.($city_id != 0 ? '|exists:cities,id' : ''),
            'city_name' => $city_id == 0 ? 'required' : '',
            'address'   => 'required',
            
            'payment_method' => 'required|in:'.implode(',', array_keys(Order::getPaymentMethods())),
            
            'recipes'   => 'required|array',
            'recipes.*' => 'numeric',
            
            'baskets'     => 'array',
            'ingredients' => 'array',
            
            'baskets.*' => 'exists:baskets,id',
            
            'terms' => 'accepted',
        ];
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'recipes.required'         => trans('validation.you must add at least one recipe'),
            'recipes.*.exists'         => trans('validation.selected recipe not exist'),
            'terms.accepted'           => trans('validation.terms accepted'),
            'coupon_code.exists'       => trans('validation.coupon code validation error'),
            'city_id.required_without' => trans('validation.city_id required without city name'),
            'city_name.required'       => trans('validation.city name is required'),
            'phone.regex'              => trans('validation.phone validation error'),
        ];
    }
}
