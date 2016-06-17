<?php

namespace App\Http\Requests\Frontend\Subscribe;

use App\Http\Requests\FormRequest;

/**
 * Class SubscribeRequest
 * @package App\Http\Requests\Frontend\Subscribe
 */
class SubscribeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
