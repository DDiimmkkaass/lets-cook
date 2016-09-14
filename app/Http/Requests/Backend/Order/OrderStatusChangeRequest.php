<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 14.07.16
 * Time: 16:19
 */

namespace App\Http\Requests\Backend\Order;

use App\Http\Requests\FormRequest;
use App\Models\Order;

/**
 * Class OrderStatusChangeRequest
 * @package App\Http\Requests\Backend\Order
 */
class OrderStatusChangeRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => 'required|in:'.implode(',', Order::getStatuses()),
        ];
        
        return $rules;
    }
}