<?php
/**
 * d by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Banner;

use App\Http\Requests\FormRequest;

/**
 * Class BannerRequest
 * @package App\Http\Requests\Backend\Banner
 */
class BannerRequest extends FormRequest
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
            'title' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        $items_rules = [];

        $itemsLanguageRules = [
            'text' => 'required_without:items.%s.%s.image',
        ];

        foreach ($this->request->get('items', []) as $array_key => $items) {
            if ($array_key !== 'remove') {
                foreach ($items as $item_id => $item) {
                    $key = sprintf('items.%s.%s.status', $array_key, $item_id);
                    $items_rules[$key] = 'required|boolean';

                    $key = sprintf('items.%s.%s.position', $array_key, $item_id);
                    $items_rules[$key] = 'required|boolean';

                    foreach (config('app.locales') as $locale) {
                        foreach ($itemsLanguageRules as $name => $rule) {
                            $items_rules['items.'.$array_key.'.'.$item_id.'.'.$locale.'.'.$name] = sprintf($rule, $array_key, $item_id);
                            $items_rules['items.'.$array_key.'.'.$item_id.'.'.$locale.'.'.$name] = sprintf($rule, $array_key, $item_id);
                        }
                    }
                }
            }
        }

        return array_merge($rules, $items_rules);
    }
}