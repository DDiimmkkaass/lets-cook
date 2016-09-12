<?php

namespace App\Http\Requests\Frontend\Order;

use App\Http\Requests\FormRequest;
use App\Models\Order;
use App\Services\WeeklyMenuService;
use Sentry;

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
        $user_id = Sentry::getUser()->getId();
        
        $weekly_menu = $this->weeklyMenuService->getWeeklyMenuByBasketId($this->request->get('basket_id', 0));
        $city_id = $this->request->get('city_id', '');
        
        $rules = [
            'basket_id'   => 'required|exists:weekly_menu_baskets,id',
            'verify_call' => 'boolean',
            
            'delivery_date' => [
                'required',
                'date_format:"d-m-Y"',
                'delivery_date_day_of_week',
                'delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week,
                'max_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week,
                'min_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week,
            ],
            'delivery_time' => 'required|in:'.implode(',', config('order.delivery_times')),
            
            'city_id'   => 'required_without:city_name'.($city_id != 0 ? '|exists:cities,id' : ''),
            'city_name' => $city_id == 0 ? 'required' : '',
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
