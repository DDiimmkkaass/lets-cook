<?php
/**
 * Updated by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Requests\Backend\Variable;

use App\Http\Requests\FormRequest;
use App\Models\Variable;

/**
 * Class VariableUpdateRequest
 * @package App\Http\Requests\Backend\Variable
 */
class VariableUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('variable');

        return [
            'type'         => 'required|in:'.implode(',', array_keys(Variable::$types)),
            'key'          => 'required|unique:variables,key,'.$id.',id',
            'name'         => 'required',
            'multilingual' => 'required|boolean',
        ];
    }
}