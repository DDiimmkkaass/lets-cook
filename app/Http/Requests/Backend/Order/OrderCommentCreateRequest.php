<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 14.07.16
 * Time: 16:19
 */

namespace App\Http\Requests\Backend\Order;

use App\Http\Requests\FormRequest;

/**
 * Class OrderCommentCreateRequest
 * @package App\Http\Requests\Backend\Order
 */
class OrderCommentCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'order_id'      => 'required|exists:orders,id',
            'order_comment' => 'required',
        ];
        
        return $rules;
    }
}