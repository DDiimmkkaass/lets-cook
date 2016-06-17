<?php

namespace App\Http\Requests\Frontend\Comment;

use App\Http\Requests\FormRequest;

/**
 * Class CommentCreateRequest
 * @package App\Http\Requests\Frontend\Comment
 */
class CommentCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id'        => 'exists:comments,id',
            'commentable_id'   => 'required',
            'commentable_type' => 'required',
            'comment'          => 'required',
        ];
    }
}
