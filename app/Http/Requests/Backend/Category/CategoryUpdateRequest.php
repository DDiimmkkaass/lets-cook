<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Category;

use App\Http\Requests\FormRequest;

/**
 * Class CategoryUpdateRequest
 * @package App\Http\Requests\Backend\Category
 */
class CategoryUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('category');

        return [
            'name'     => 'required|unique:categories,name,'.$id.',id',
            'position' => 'integer',
        ];
    }
}