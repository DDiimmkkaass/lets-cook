<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Requests\Backend\Article;

use App\Http\Requests\FormRequest;

/**
 * Class ArticleUpdateRequest
 * @package App\Http\Requests\Backend\Article
 */
class ArticleUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('article');

        $rules = [
            'status'     => 'required|boolean',
            'slug'       => 'unique:articles,slug,'.$id.',id',
            'position'   => 'required|integer',
            'image'      => ['regex:'.$this->image_regex],
            'publish_at' => 'date_format:d-m-Y',
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