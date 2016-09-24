<?php

namespace App\Http\Requests\Frontend\BasketSubscribe;

use App\Http\Requests\FormRequest;
use App\Models\BasketSubscribe;

/**
 * Class BasketSubscribeUpdateRequest
 * @package App\Http\Requests\Frontend\BasketSubscribe
 */
class BasketSubscribeUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'basket_id'        => 'required|exists:baskets,id',
            'subscribe_period' => 'required|in:'.implode(',', BasketSubscribe::getSubscribePeriods()),
            'delivery_date'    => 'required|in:0,1',
            'delivery_time'    => 'required|in:'.implode(',', config('order.delivery_times')),
            'portions'         => 'numeric',
            'recipes'          => 'numeric',
            
            'baskets'   => 'array',
            'baskets.*' => 'exists:baskets,id',
        ];
    }
}
