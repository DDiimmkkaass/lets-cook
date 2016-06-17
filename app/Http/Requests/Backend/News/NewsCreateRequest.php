<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Requests\Backend\News;

use App\Http\Requests\FormRequest;

/**
 * Class NewsCreateRequest
 * @package App\Http\Requests\Backend\News
 */
class NewsCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';

        $rules = [
            'status'     => 'required|boolean',
            'slug'       => 'unique:pages,slug',
            'position'   => 'required|integer',
            'image'      => ['regex:'.$regex],
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