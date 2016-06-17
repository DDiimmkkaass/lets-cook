<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Group;

use App\Http\Requests\FormRequest;

/**
 * Class GroupUpdateRequest
 * @package App\Http\Requests\Group
 */
class GroupUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('group');

        return [
            'name' => 'required|min:2|unique:groups,name,'.$id,
        ];
    }
}