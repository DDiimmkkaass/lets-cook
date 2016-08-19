<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Parameter;

use App\Http\Requests\FormRequest;

/**
 * Class ParameterUpdateRequest
 * @package App\Http\Requests\Backend\Parameter
 */
class ParameterUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('parameter');

        return [
            'name'     => 'required|unique:parameters,name,'.$id.',id',
            'package'  => 'required|integer|in:1,2',
            'position' => 'integer',
        ];
    }
}