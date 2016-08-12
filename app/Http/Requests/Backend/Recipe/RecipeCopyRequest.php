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
 * Class RecipeCopyRequest
 * @package App\Http\Requests\Backend\Recipe
 */
class RecipeCopyRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'portions' => 'required|numeric|min:2',
            'bind'     => 'required|boolean',
        ];
    }
}