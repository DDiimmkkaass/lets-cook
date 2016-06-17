<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Tag;

use App\Http\Requests\FormRequest;

/**
 * Class TagUpdateRequest
 * @package App\Http\Requests\Backend\Tag
 */
class TagUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('tag');

        $rules = [
            'status'   => 'required|boolean',
            'position' => 'integer',
            'slug'     => 'required|unique:tags,slug,'.$id.',id',
        ];

        $languageRules = [
            'name' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        return $rules;
    }
}