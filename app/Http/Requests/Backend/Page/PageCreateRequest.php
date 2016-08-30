<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Page;

use App\Http\Requests\FormRequest;

/**
 * Class PageCreateRequest
 * @package App\Http\Requests\Backend\Page
 */
class PageCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $templates = get_templates(base_path('resources/themes/'.config('app.theme').'/views/page/templates'), true);
        
        $rules = [
            'status'   => 'required|boolean',
            'slug'     => 'unique:pages,slug',
            'position' => 'required|integer',
            'template' => 'required|in:'.implode(',', $templates),
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