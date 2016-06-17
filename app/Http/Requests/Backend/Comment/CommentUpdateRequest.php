<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Comment;

use App\Http\Requests\FormRequest;

/**
 * Class CommentUpdateRequest
 * @package App\Http\Requests\Backend\Comment
 */
class CommentUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'comment' => 'required',
            'status'  => 'required|boolean',
        ];

        return $rules;
    }
}