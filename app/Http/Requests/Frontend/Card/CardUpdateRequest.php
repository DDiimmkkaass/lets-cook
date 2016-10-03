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
        $card_id = $this->route('card_id');
        $user_id = Sentry::getUser()->getId();
        
        $rules = [
            'name'    => 'required|unique:cards,name,'.$card_id.',id,user_id,'.$user_id,
            'number'  => 'numeric|regex:/^[0-9]{0,16}$/',
            'default' => 'required|boolean',
        ];
        
        return $rules;
    }
}