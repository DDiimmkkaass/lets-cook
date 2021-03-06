<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 19:13
 */

namespace App\Http\Requests\Backend\Recipe;

use App\Http\Requests\FormRequest;

/**
 * Class RecipeRequest
 * @package App\Http\Requests\Backend\Recipe
 */
class RecipeRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request('draft', false)) {
            return [
                'image'        => ['regex:'.$this->image_regex],
                'portions'     => 'numeric|min:0',
                'cooking_time' => 'numeric|min:0',
                'status'       => 'boolean',

                'parent_id' => 'required_if:bind,true|exists:recipes,id',
                'bind'      => 'boolean',
                
                'baskets' => 'array',
                
                'steps'            => 'array',
                'ingredients'      => 'array',
                'ingredients_home' => 'array',
                'tags'             => 'array',
                'files'            => 'array',
                
                'steps.old.*.image'    => ['regex:'.$this->image_regex],
                'steps.old.*.position' => 'numeric|min:0',
                
                'steps.new.*.image'    => ['regex:'.$this->image_regex],
                'steps.new.*.position' => 'numeric|min:0',
                
                'steps.remove' => 'array',
                
                'ingredients.old.*.ingredient_id' => 'exists:ingredients,id',
                'ingredients.old.*.count'         => 'numeric|min:0',
                'ingredients.old.*.position'      => 'numeric|min:0',
                
                'ingredients.new.*.ingredient_id' => 'exists:ingredients,id',
                'ingredients.new.*.count'         => 'numeric|min:0',
                'ingredients.new.*.position'      => 'numeric|min:0',
                
                'ingredients.remove' => 'array',
                
                'ingredients_home.old.*.ingredient_id' => 'exists:ingredients,id',
                'ingredients_home.old.*.count'         => 'numeric|min:0',
                'ingredients_home.old.*.position'      => 'numeric|min:0',
                
                'ingredients_home.new.*.ingredient_id' => 'exists:ingredients,id',
                'ingredients_home.new.*.count'         => 'numeric|min:0',
                'ingredients_home.new.*.position'      => 'numeric|min:0',
                
                'ingredients_home.remove' => 'array',
                
                'tags.*' => 'exists:tags,id',
                
                'files.old.*.position' => 'numeric|min:0',
                
                'files.new.*.position' => 'numeric|min:0',
                
                'files.remove' => 'array',
            ];
        }
        
        return [
            'name'         => 'required',
            'image'        => ['regex:'.$this->image_regex],
            'portions'     => 'required|numeric|in:'.implode(',', config('recipe.available_portions')),
            'cooking_time' => 'required|numeric|min:0',
            'status'       => 'required|boolean',
            
            'parent_id' => 'required_if:bind,true|exists:recipes,id',
            'bind'      => 'boolean',
            
            'baskets' => 'required|array',
            
            'steps'            => 'required|array',
            'ingredients'      => 'required|array',
            'ingredients_home' => 'array',
            'tags'             => 'array',
            'files'            => 'array',
            
            'steps.old.*.description' => 'required',
            'steps.old.*.image'       => ['regex:'.$this->image_regex],
            'steps.old.*.position'    => 'required|numeric|min:0',
            
            'steps.new.*.description' => 'required',
            'steps.new.*.image'       => ['regex:'.$this->image_regex],
            'steps.new.*.position'    => 'required|numeric|min:0',
            
            'steps.remove' => 'array',
            
            'ingredients.old.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.old.*.count'         => 'required|numeric|min:0',
            'ingredients.old.*.position'      => 'required|numeric|min:0',
            
            'ingredients.new.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.new.*.count'         => 'required|numeric|min:0',
            'ingredients.new.*.position'      => 'required|numeric|min:0',
            
            'ingredients.remove' => 'array',
            
            'ingredients_home.old.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients_home.old.*.count'         => 'required|numeric|min:0',
            'ingredients_home.old.*.position'      => 'required|numeric|min:0',
            
            'ingredients_home.new.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients_home.new.*.count'         => 'required|numeric|min:0',
            'ingredients_home.new.*.position'      => 'required|numeric|min:0',
            
            'ingredients_home.remove' => 'array',
            
            'tags.*' => 'exists:tags,id',
            
            'files.old.*.name'     => 'required',
            'files.old.*.src'      => 'required',
            'files.old.*.position' => 'numeric|min:0',
            
            'files.new.*.name'     => 'required',
            'files.new.*.src'      => 'required',
            'files.new.*.position' => 'numeric|min:0',
            
            'files.remove' => 'array',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'baskets.required'         => trans('validation.recipe baskets'),
            'steps.required'           => trans('validation.recipe steps'),
            'ingredients.required'     => trans('validation.recipe ingredients'),
        ];
    }
}