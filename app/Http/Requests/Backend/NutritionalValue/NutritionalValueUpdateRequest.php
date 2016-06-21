<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 12:46
 */

namespace App\Http\Requests\Backend\NutritionalValue;

use App\Http\Requests\FormRequest;

/**
 * Class NutritionalValueUpdateRequest
 * @package App\Http\Requests\Backend\NutritionalValue
 */
class NutritionalValueUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('nutritional_value');

        return [
            'name'     => 'required|unique:nutritional_values,name,'.$id.',id',
            'position' => 'integer',
        ];
    }
}