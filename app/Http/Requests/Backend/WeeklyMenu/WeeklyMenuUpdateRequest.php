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
 * Class WeeklyMenuUpdateRequest
 * @package App\Http\Requests\Backend\WeeklyMenu
 */
class WeeklyMenuUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('weekly_menu');
        
        return [
            'week' => 'required|numeric|min:1|max:52|unique:weekly_menus,week,'.$id.',id,year,' . $this->request->get('year'),
            'year' => 'required|numeric',

            'baskets.*.new.*' => 'array',
            'baskets.*.old.*' => 'array',
            'baskets.*.remove' => 'array',

            'baskets.*.id'       => 'required|exists:baskets,id',
            'baskets.*.portions' => 'required|numeric|in:'.implode(',', config('recipe.available_portions')),

            'baskets.*.old.*.recipe_id' => 'required_with:baskets.*.new|exists:recipes,id',
            'baskets.*.old.*.position'  => 'required_with:baskets.*.new|numeric|min:1',
            'baskets.*.old.*.main'      => 'boolean',

            'baskets.*.new.*.recipe_id' => 'required_with:baskets.*.new|exists:recipes,id',
            'baskets.*.new.*.position'  => 'required_with:baskets.*.new|numeric|min:1',
            'baskets.*.new.*.main'      => 'boolean',
        ];
    }
}