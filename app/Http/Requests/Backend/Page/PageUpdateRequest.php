<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Page;

use App\Http\Requests\FormRequest;
use Config;

/**
 * Class PageUpdateRequest
 * @package App\Http\Requests\Backend\Page
 */
class PageUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('page');

        $rules = [
            'status'   => 'required|boolean',
            'slug'     => 'unique:pages,slug,'.$id.',id',
            'position' => 'required|integer',
            'image'    => ['regex:'.$this->image_regex],
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