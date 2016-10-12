<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\TagCategory;

use App\Http\Requests\FormRequest;

/**
 * Class TagCategoryCreateRequest
 * @package App\Http\Requests\Backend\TagCategory
 */
class TagCategoryCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|unique:tag_categories,name',
            'status'   => 'required|boolean',
            'position' => 'integer',
        ];
    }
}