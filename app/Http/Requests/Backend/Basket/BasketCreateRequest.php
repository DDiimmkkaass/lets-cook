<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.06.16
 * Time: 14:47
 */

namespace App\Http\Requests\Backend\Basket;

use App\Http\Requests\FormRequest;
use App\Models\Basket;

/**
 * Class BasketCreateRequest
 * @package App\Http\Requests\Backend\Basket
 */
class BasketCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type = $this->request->get('type', null);
        
        $rules = [
            'name'     => 'required|unique:baskets,name',
            'position' => 'integer',
            'type'     => 'required|in:'.implode(',', Basket::$types),
            
            'recipes.new.*.recipe_id' => 'required_with:recipes.new|exists:recipes,id',
            'recipes.new.*.main'      => 'boolean',
            'recipes.new.*.position'  => 'required_with:recipes.new|numeric|min:0',
            
            'tags' => 'array',

            'tags.*' => 'exists:tags,id',
        ];
        
        if (Basket::getTypeIdByName($type) == 'basic') {
            $rules['prices'] = 'array';
            
            foreach (config('recipe.available_portions') as $portion) {
                foreach (range(1, config('weekly_menu.menu_days')) as $day) {
                    $rules['prices.'.$portion.'.'.$day] = 'required|numeric|min:0';
                }
            }
        }
        
        if (Basket::getTypeIdByName($type) == 'additional') {
            $rules['price'] = 'required|numeric|min:0';
        }
        
        return $rules;
    }
}