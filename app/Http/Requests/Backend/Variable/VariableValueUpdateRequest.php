<?php
/**
 * Updated by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Requests\Backend\Variable;

use App\Http\Requests\FormRequest;
use App\Models\Variable;

/**
 * Class VariableValueUpdateRequest
 * @package App\Http\Requests\Backend\Variable
 */
class VariableValueUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $type = $this->request->get('type', 'text');
        $multilingual = $this->request->get('multilingual', false);

        if ($multilingual) {
            foreach (config('app.locales') as $locale) {
                $rules[$locale.'.text'] = 'required';
            }
        } else {
            $rules['value'] = 'required';
        }

        if ($type == 'image') {
            $regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';

            $rules = [
                'value' => 'required|regex:'.$regex
            ];
        }

        $rules['status'] = 'required|boolean';
        $rules['variable_id'] = 'required|exists:variables,id';

        return $rules;
    }
}