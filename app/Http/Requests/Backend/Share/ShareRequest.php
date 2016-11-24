<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Requests\Backend\Share;

use App\Http\Requests\FormRequest;

/**
 * Class ShareRequest
 * @package App\Http\Requests\Backend\Share
 */
class ShareRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image'    => ['required', 'regex:'.$this->image_regex],
            'link'     => 'required|string',
            'position' => 'required|integer',
            'status'   => 'required|boolean',
        ];
    }
}