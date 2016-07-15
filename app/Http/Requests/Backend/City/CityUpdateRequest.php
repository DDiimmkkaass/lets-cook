<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\City;

use App\Http\Requests\FormRequest;

/**
 * Class CityUpdateRequest
 * @package App\Http\Requests\Backend\City
 */
class CityUpdateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('city');
        
        return [
            'name'     => 'required|unique:cities,name,'.$id.',id',
            'position' => 'integer',
        ];
    }
}