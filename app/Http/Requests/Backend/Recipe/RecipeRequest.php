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
        return [
            'name'         => 'required',
            'image'        => ['regex:'.$this->image_regex],
            'recipe'       => 'required',
            'portions'     => 'required|numeric|min:0',
            'cooking_time' => 'required|numeric|min:0',
            'status'       => 'required|boolean',

            'baskets' => 'required|array',

            'main_ingredient' => 'required',

            'steps'       => 'required|array',
            'ingredients' => 'required|array',

            'steps.*.*.name'        => 'required',
            'steps.*.*.description' => 'required',
            'steps.*.*.image'       => ['required', 'regex:'.$this->image_regex],
            'steps.*.*.position'    => 'required|numeric|min:0',

            'ingredients.*.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.*.count'         => 'required|numeric|min:0',
            'ingredients.*.*.position'      => 'required|numeric|min:0',
            'ingredients.*.*.main'          => 'boolean',
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
            'main_ingredient.required' => trans('validation.recipe main ingredient'),
            'baskets.required'         => trans('validation.recipe baskets'),
            'steps.required'           => trans('validation.recipe steps'),
            'ingredients.required'     => trans('validation.recipe ingredients'),
        ];
    }
}