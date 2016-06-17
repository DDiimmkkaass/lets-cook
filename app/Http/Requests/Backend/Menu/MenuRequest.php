<?php
/**
 * d by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Menu;

use App\Http\Requests\FormRequest;

/**
 * Class MenuRequest
 * @package App\Http\Requests\Backend\Menu
 */
class MenuRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'layout_position' => 'required',
            'show_title'      => 'required|boolean',
            'position'        => 'required|integer',
            'status'          => 'required|boolean',
            'template'        => 'required',
        ];

        $languageRules = [
            'name' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        $items_rules = [
            'items.new.*.link'     => 'required',
            'items.new.*.status'   => 'required|boolean',
            'items.new.*.position' => 'required|integer',
            'items.old.*.link'     => 'required',
            'items.old.*.status'   => 'required|boolean',
            'items.old.*.position' => 'required|integer',
        ];

        $itemsLanguageRules = [
            'name' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($itemsLanguageRules as $name => $rule) {
                $items_rules['items.new.*.'.$locale.'.'.$name] = $rule;
                $items_rules['items.old.*.'.$locale.'.'.$name] = $rule;
            }
        }

        return array_merge($rules, $items_rules);
    }
}