<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.02.16
 * Time: 13:55
 */

namespace App\Http\Requests;

use FlashMessages;
use Illuminate\Foundation\Http\FormRequest as IlluminateRequest;

/**
 * Class FormRequest
 * @package App\Http\Requests
 */
class FormRequest extends IlluminateRequest
{
    /**
     * @var string
     */
    protected $image_regex;

    /**
     * FormRequest constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->image_regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if (!$this->ajax() && !$this->wantsJson()) {
            FlashMessages::add("error", trans("messages.validation_failed"));
        }

        foreach ($errors as $key => $error) {
            preg_match_all('/.*\s([a-zA-z0-9\.*]+)\s.*/iUs', $errors[$key][0], $matches);

            if (isset($matches[1]) && !empty($matches[1])) {
                foreach ($matches[1] as $match) {
                    $title = explode('.', $match);
                    $title = trans('validation.attributes.'.array_pop($title));

                    $error = str_replace($match, $title, $error);
                }
            }

            $errors[$key] = $error;
        }

        return parent::response($errors);
    }
}