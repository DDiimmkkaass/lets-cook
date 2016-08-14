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
 * Class TagCategoryUpdateRequest
 * @package App\Http\Requests\Backend\TagCategory
 */
class TagCategoryUpdateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('tag_category');
        
        return [
            'name'     => 'required|unique:tag_categories,name,'.$id.',id',
            'position' => 'integer',
        ];
    }
}