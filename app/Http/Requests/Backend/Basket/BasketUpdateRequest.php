<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Basket;

use App\Http\Requests\FormRequest;
use App\Models\Basket;

/**
 * Class BasketUpdateRequest
 * @package App\Http\Requests\Backend\Basket
 */
class BasketUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('basket');
        $type = request()->get('type', null);
    
        $rules = [
            'name'     => 'required|unique:baskets,name,'.$id.',id',
            'position' => 'integer',

            'recipes.new' => 'array',
            'recipes.old' => 'array',
            'tags' => 'array',
            
            'recipes.old.*.recipe_id' => 'required_with:recipes.old|exists:recipes,id',
            'recipes.old.*.main'      => 'boolean',
            'recipes.old.*.position'  => 'required_with:recipes.old|numeric|min:0',

            'recipes.new.*.recipe_id' => 'required_with:recipes.new|exists:recipes,id',
            'recipes.new.*.main'      => 'boolean',
            'recipes.new.*.position'  => 'required_with:recipes.new|numeric|min:0',

            'tags.*' => 'exists:tags,id',
        ];
    
        if ($type == 'basic') {
            $rules['prices'] = 'array';
        
            foreach (config('recipe.available_portions') as $portion) {
                foreach (range(1, config('weekly_menu.menu_days')) as $day) {
                    $rules['prices.'.$portion.'.'.$day] = 'required|numeric|min:0';
                }
            }
    
            $rules['places'] = 'array';
    
            foreach (config('recipe.available_portions') as $portion) {
                foreach (range(1, config('weekly_menu.menu_days')) as $day) {
                    $rules['places.'.$portion.'.'.$day] = 'required|integer|min:1|max:3';
                }
            }
        }
    
        if ($type == 'additional') {
            $rules['price'] = 'required|numeric|min:0';
    
            $rules['places'] = 'required|integer|min:1|max:3';
        }
        
        return $rules;
    }
}