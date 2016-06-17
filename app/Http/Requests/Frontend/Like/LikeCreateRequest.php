<?php

namespace App\Http\Requests\Frontend\Like;

use App\Http\Requests\FormRequest;

/**
 * Class LikeCreateRequest
 * @package App\Http\Requests\Frontend\Like
 */
class LikeCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'likable_id'   => 'required|numeric',
            'likable_type' => 'required',
        ];
    }
}
