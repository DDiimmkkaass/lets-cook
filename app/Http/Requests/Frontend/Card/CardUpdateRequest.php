<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\Card;

use App\Http\Requests\FormRequest;
use Sentry;

/**
 * Class CardUpdateRequest
 * @package App\Http\Requests\Frontend\User
 */
class CardUpdateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'    => 'required',
            'number'  => 'regex:/^[0-9]{0,16}$/',
            'default' => 'required|boolean',
        ];
        
        return $rules;
    }
}