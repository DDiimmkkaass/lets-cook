<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.08.16
 * Time: 16:42
 */

namespace App\Http\Requests\Backend\Purchase;

use App\Http\Requests\FormRequest;

/**
 * Class PurchasePriceUpdateRequest
 * @package App\Http\Requests\Backend\Purchase
 */
class PurchasePriceUpdateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => 'required|numeric|min:0',
        ];
    }
}