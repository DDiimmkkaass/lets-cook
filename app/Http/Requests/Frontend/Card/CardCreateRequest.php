<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\Card;

use App\Http\Requests\FormRequest;
use Carbon;
use Sentry;

/**
 * Class CardCreateRequest
 * @package App\Http\Requests\Frontend\User
 */
class CardCreateRequest extends FormRequest
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
            'number'  => 'string|max:4',
            'default' => 'required|boolean',
        ];
        
        return $rules;
    }
}