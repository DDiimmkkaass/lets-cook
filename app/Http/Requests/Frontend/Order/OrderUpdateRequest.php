<?php

namespace App\Http\Requests\Frontend\Order;

use App\Http\Requests\FormRequest;
use App\Models\Order;
use App\Services\WeeklyMenuService;

/**
 * Class OrderUpdateRequest
 * @package App\Http\Requests\Frontend\Order
 */
class OrderUpdateRequest extends FormRequest
{
    /**
     * @var \App\Services\WeeklyMenuService
     */
    private $weeklyMenuService;
    
    /**
     * OrderUpdateRequest constructor.
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
        $city_id = $this->request->get('city_id', 0);
    
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
            'basket_id' => 'required|exists:weekly_menu_baskets,id',
            
            'delivery_date' => $delivery_rules,
            'delivery_time' => 'required|in:'.implode(',', config('order.delivery_times')),
            
            'city_name' => $city_id == 0 ? 'required' : '',
            'address'   => 'required',
            
            'payment_method' => 'required|in:'.implode(',', array_keys(Order::getPaymentMethods())),
            
            'baskets'   => 'array',
            'baskets.*' => 'exists:baskets,id',
        ];
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'city_name.required' => trans('validation.city name is required'),
        ];
    }
}
