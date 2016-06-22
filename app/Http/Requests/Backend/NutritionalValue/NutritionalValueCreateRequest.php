<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\NutritionalValue;

use App\Http\Requests\FormRequest;

/**
 * Class NutritionalValueCreateRequest
 * @package App\Http\Requests\Backend\NutritionalValue
 */
class NutritionalValueCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|unique:nutritional_values,name',
            'position' => 'integer',
        ];
    }
}