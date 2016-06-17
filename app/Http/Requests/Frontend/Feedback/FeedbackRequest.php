<?php

namespace App\Http\Requests\Frontend\Feedback;

use App\Http\Requests\FormRequest;

/**
 * Class FeedbackRequest
 * @package App\Http\Requests\Frontend\Feedback
 */
class FeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fio'     => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ];
    }
}
