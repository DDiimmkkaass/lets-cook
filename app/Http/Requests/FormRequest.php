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
     * @var string
     */
    protected $file_regex;
    
    /**
     * FormRequest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->_setImageRegexp();
        
        $this->_setFileRegexp();
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
        foreach ($errors as $key => $error) {
            foreach ($error as $_key => $e) {
                preg_match_all('/.*\s([a-zA-z0-9\.]{3,})\s.*/iUs', $e, $matches);
    
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $match) {
                        $title = explode('.', $match);
                        $title = trans('validation.attributes.'.array_pop($title));
    
                        $errors[$key][$_key] = str_replace($match, $title, $e);
                    }
                }
            }
        }
    
        $message = trans("messages.validation_failed");

        if (!$this->ajax() && !$this->wantsJson()) {
            if (!is_admin_panel()) {
                foreach ($errors as $key => $error) {
                    foreach ($error as $_key => $e) {
                        $message .= '<br >'.$e;
                    }
                }
            }
        
            FlashMessages::add("error", $message);
        }
        
        return parent::response($errors);
    }
    
    /**
     * set image regexp based on current env
     */
    private function _setImageRegexp()
    {
        $this->image_regex = env('APP_ENV') != 'production' ?
            '/.+/' :
            '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';
    }
    
    private function _setFileRegexp()
    {
        $this->file_regex = '/^.*\.('.implode('|', config('recipe.allowed_file_extension')).')$/';;
    }
}