<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.10.16
 * Time: 17:29
 */

namespace App\Http\Requests\Backend\Booklet;

use App\Http\Requests\FormRequest;

/**
 * Class BookletUpdateRequest
 * @package App\Http\Requests\Backend\Booklet
 */
class BookletUpdateRequest extends FormRequest
{
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'link' => 'required|url',
            'year' => 'required|numeric',
            'week' => 'required|numeric|min:1|max:52',
        ];
    }
}