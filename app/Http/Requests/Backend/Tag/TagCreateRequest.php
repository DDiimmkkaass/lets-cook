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
 * Class TagCreateRequest
 * @package App\Http\Requests\Backend\Tag
 */
class TagCreateRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'category_id' => 'exists:tag_categories,id',
        ];
        
        $languageRules = [
            'name' => 'required|unique:tag_translations,name',
        ];
        
        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }
        
        return $rules;
    }
}