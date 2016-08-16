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

/**
 * Class OrderUpdateRequest
 * @package App\Http\Requests\Backend\Order
 */
class OrderUpdateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        
        $rules = [
            'parent_id'        => 'exists:orders,id',
            'user_id'          => 'required|exists:users,id',
            'type'             => 'required|in:'.implode(',', array_keys(Order::getTypes())),
            'subscribe_period' => 'numeric|min:1|required_if:type,'.Order::getTypeIdByName('subscribe'),
            'status'           => 'required|in:'.implode(',', array_keys(Order::getStatuses())),
            'payment_method'   => 'required|in:'.implode(',', array_keys(Order::getPaymentMethods())),
            'full_name'        => 'required',
            'email'            => 'required',
            'phone'            => 'required',
            'verify_call'      => 'boolean',
            
            'delivery_date' => 'required|date_format:"d-m-Y"',
            'delivery_time' => 'required',
            'city'          => 'required_without:city_id',
            'address'       => 'required',
            
            'recipes.new.*' => 'array',
            'recipes.old.*' => 'array',
            'recipes.remove' => 'array',

            'baskets' => 'array',
            
            'ingredients.new.*' => 'array',
            'ingredients.old.*' => 'array',
            'ingredients.remove' => 'array',
            
            'recipes.old.*.basket_recipe_id' => 'required|exists:basket_recipes,id',
            'recipes.new.*.basket_recipe_id' => 'required|exists:basket_recipes,id',
    
            'baskets.*' => 'exists:baskets,id',

            'ingredients.old.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.old.*.count'         => 'required|numeric|min:1',
            'ingredients.new.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.new.*.count'         => 'required|numeric|min:1',
        ];
        
        return $rules;
    }
}