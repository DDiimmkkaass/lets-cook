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
            'week' => 'required|regex:/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}\s\-\s[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/',

            'started_at' => 'required|unique:weekly_menus,started_at|before:ended_at',
            'ended_at'   => 'required|unique:weekly_menus,ended_at|after:started_at|diff_in_days:started_at,6',

            'baskets.*.new.*' => 'array',

            'baskets.*.new.*.recipe_id' => 'required_with:baskets.*.new|exists:recipes,id',
            'baskets.*.new.*.position'  => 'required_with:baskets.*.new|numeric|min:0',
        ];

        return $rules;
    }
}