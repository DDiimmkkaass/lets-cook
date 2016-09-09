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
        $weekly_menu = $this->weeklyMenuService->getWeeklyMenuByBasketId($this->request->get('basket', 0));
        
        $rules = [
            'parent_id'        => 'exists:orders,id',
            'user_id'          => 'required|exists:users,id',
            'type'             => 'required|in:'.implode(',', array_keys(Order::getTypes())),
            'subscribe_period' => 'numeric|min:1|required_if:type,'.Order::getTypeIdByName('subscribe'),
            'status'           => 'required|in:'.implode(',', array_keys(Order::getStatuses())),
            'status_comment'   => $this->request->get('status') != $this->request->get('old_status') ? 'required' : '',
            'payment_method'   => 'required|in:'.implode(',', array_keys(Order::getPaymentMethods())),
            'full_name'        => 'required',
            'email'            => 'required|email',
            'phone'            => 'required',
            'verify_call'      => 'boolean',
            
            'delivery_date' => ['required', 'date_format:"d-m-Y"'],
            'delivery_time' => 'required',
            
            'city_name' => 'required_without:city_id',
            'address'   => 'required',
            
            'recipes.new'    => 'array|required_without:recipes.old',
            'recipes.old'    => 'array|required_without:recipes.new',
            'recipes.new.*'  => 'array|required_without:recipes.old',
            'recipes.old.*'  => 'array|required_without:recipes.new',
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
        ];
        
        if ($this->request->get('delivery_date') != $this->request->get('old_delivery_date')) {
            $rules['delivery_date'][] = 'delivery_date_day_of_week';
            $rules['delivery_date'][] = 'delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week;
            $rules['delivery_date'][] = 'max_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week;
            $rules['delivery_date'][] = 'min_delivery_date_date:'.$weekly_menu->year.','.$weekly_menu->week;
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
            'recipes.old.required_without' => trans('validation.you cant remove all recipes'),
            'recipes.new.required_without' => trans('validation.you must add at least one recipe'),
        ];
    }
}