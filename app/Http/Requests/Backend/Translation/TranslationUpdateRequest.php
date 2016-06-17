<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\Translation;

use App\Http\Requests\FormRequest;
use File;

/**
 * Class TranslationUpdateRequest
 * @package App\Http\Requests\Backend\Translation
 */
class TranslationUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $_locales = $rules = [];
        
        $locales = File::directories(app()->langPath());
        foreach ($locales as $key => $locale) {
            $locale = explode('/', $locale);

            $_locales[$key] = array_pop($locale);
        }

        if (count($_locales)) {
            foreach ($_locales as $locale) {
                $rules[$locale.'.*'] = 'required';
            }
        }

        return $rules;
    }
}
