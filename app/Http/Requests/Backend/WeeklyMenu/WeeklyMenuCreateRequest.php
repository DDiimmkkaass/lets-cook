<?php
/**
 * d by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\WeeklyMenu;

use App\Http\Requests\FormRequest;

/**
 * Class WeeklyMenuCreateRequest
 * @package App\Http\Requests\Backend\WeeklyMenu
 */
class WeeklyMenuCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'week' => 'required|numeric|min:1|max:52|unique:weekly_menus,week,NULL,id,year,'.
                $this->request->get('year'),
            'year' => 'required|numeric',
            
            'baskets.*.old.*' => 'array',
            'baskets.*.new.*' => 'array',
            
            'baskets.*.id'            => 'required|exists:baskets,id',
            'baskets.*.portions'      => 'required|numeric|in:'.implode(',', config('recipe.available_portions')),
            'baskets.*.delivery_date' => 'date_format:d-m-Y',
            
            'baskets.*.old.*.recipe_id' => 'required_with:baskets.*.new|exists:recipes,id',
            'baskets.*.old.*.position'  => 'required_with:baskets.*.new|numeric|min:1',
            
            'baskets.*.new.*.recipe_id' => 'required_with:baskets.*.new|exists:recipes,id',
            'baskets.*.new.*.position'  => 'required_with:baskets.*.new|numeric|min:1',
        ];
        
        return $rules;
    }
}