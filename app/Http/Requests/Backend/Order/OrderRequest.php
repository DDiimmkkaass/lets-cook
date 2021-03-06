<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 14.07.16
 * Time: 16:19
 */

namespace App\Http\Requests\Backend\Order;

use App\Http\Requests\FormRequest;
use App\Models\Order;
use App\Services\WeeklyMenuService;

/**
 * Class OrderRequest
 * @package App\Http\Requests\Backend\Order
 */
class OrderRequest extends FormRequest
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
        $order = $this->route('order', false);
        $weekly_menu = $this->weeklyMenuService->getWeeklyMenuByBasketId($this->request->get('basket_id', 0));
        
        $rules = [
            'parent_id'      => 'exists:orders,id',
            'user_id'        => 'required|exists:users,id',
            'status'         => 'required|in:'.implode(',', array_keys(Order::getStatuses())),
            'status_comment' => ($this->request->get('status') != $this->request->get('old_status') && $order) ?
                'required' :
                '',
            'payment_method' => 'required|in:'.implode(',', array_keys(Order::getPaymentMethods())),
            'full_name'      => 'required',
            'email'          => 'required|email',
            'phone'          => 'required',
            'verify_call'    => 'boolean',
            
            'delivery_date' => ['required', 'date_format:"d-m-Y"'],
            'delivery_time' => 'required',
            
            'city_id'   => 'required_without:city_name|exists:cities,id',
            'city_name' => 'required_without:city_id',
            'address'   => 'required',
            
            'recipes' => 'min_recipes_count|max_recipes_count',
            
            'recipes.remove' => 'array',
            
            'baskets' => 'array',
            
            'ingredients.new.*'  => 'array',
            'ingredients.old.*'  => 'array',
            'ingredients.remove' => 'array',
            
            'recipes.old.*.basket_recipe_id' => 'required|exists:basket_recipes,id',
            'recipes.new.*.basket_recipe_id' => 'required|exists:basket_recipes,id',
            
            'baskets.*' => 'exists:baskets,id',
            
            'ingredients.old.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.old.*.count'         => 'required|numeric|min:1',
            'ingredients.new.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.new.*.count'         => 'required|numeric|min:1',
            
            'coupon_id'   => 'exists:coupons,id',
            'coupon_code' => 'exists:coupons,code',
        ];
        
        if ($this->request->get('delivery_date') != $this->request->get('old_delivery_date')) {
            $rules['delivery_date'][] = 'delivery_date_day_of_week';
            $rules['delivery_date'][] = 'delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week;
            $rules['delivery_date'][] = 'max_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week;
            $rules['delivery_date'][] = 'min_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week;
        }
        
        return $rules;
    }
}