<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Basket;

use App\Http\Requests\FormRequest;

/**
 * Class BasketUpdateRequest
 * @package App\Http\Requests\Backend\Basket
 */
class BasketUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('basket');

        return [
            'name'     => 'required|unique:baskets,name,'.$id.',id',
            'price'    => 'required|numeric|min:0',
            'position' => 'integer',

            'recipes.new' => 'array',
            'recipes.old' => 'array',

            'recipes.old.*.recipe_id' => 'required_with:recipes.old|exists:recipes,id',
            'recipes.old.*.main'      => 'boolean',
            'recipes.old.*.position'  => 'required_with:recipes.old|numeric|min:0',

            'recipes.new.*.recipe_id' => 'required_with:recipes.new|exists:recipes,id',
            'recipes.new.*.main'      => 'boolean',
            'recipes.new.*.position'  => 'required_with:recipes.new|numeric|min:0',
        ];
    }
}