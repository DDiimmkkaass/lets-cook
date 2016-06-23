<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.06.16
 * Time: 14:47
 */

namespace App\Http\Requests\Backend\Basket;

use App\Http\Requests\FormRequest;

/**
 * Class BasketCreateRequest
 * @package App\Http\Requests\Backend\Basket
 */
class BasketCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|unique:baskets,name',
            'position' => 'integer',
        ];
    }
}