<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 19:13
 */

namespace App\Http\Requests\Backend\Ingredient;

use App\Http\Requests\FormRequest;

/**
 * Class IngredientRequest
 * @package App\Http\Requests\Backend\Ingredient
 */
class IngredientRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'numeric|min:0',
            'image'       => ['regex:'.$this->image_regex],
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_id'     => 'required|exists:units,id',
            
            'additional_parameter' => 'exists:parameters,id',
            
            'nutritional_values.*.id'    => 'exists:nutritional_values,id',
            'nutritional_values.*.value' => 'numeric',
        ];
    }
}