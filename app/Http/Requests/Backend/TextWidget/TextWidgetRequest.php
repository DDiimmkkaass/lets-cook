<?php
/**
 * d by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\TextWidget;

use App\Http\Requests\FormRequest;

/**
 * Class TextWidgetRequest
 * @package App\Http\Requests\Backend\TextWidget
 */
class TextWidgetRequest extends FormRequest
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
            'status'          => 'required|boolean',
            'position'        => 'required|integer',
        ];

        $languageRules = [
            'title'   => 'required_without:%s.content',
            'content' => 'required_without:%s.title',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = sprintf($rule, $locale);
            }
        }

        return $rules;
    }
}