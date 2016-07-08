<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 08.07.16
 * Time: 14:30
 */

namespace App\Validators;

use Carbon;
use Illuminate\Validation\Validator;

/**
 * Class DiffInDaysValidator
 * @package App\Validators
 */
class DiffInDaysValidator extends Validator
{
    
    /**
     * @param string $attribute
     * @param string $value
     * @param array  $parameters
     *
     * @return bool
     */
    public function validateDiffInDays($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'exists');

        $param = $this->getValue($parameters[0]) ? : $parameters[0];

        $diff = $parameters[1];

        return Carbon::createFromFormat('Y-m-d H:i:s', $value)
            ->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s', $param)) == $diff;
    }

    /**
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    public function replaceDiffInDays($message, $attribute, $rule, $parameters)
    {
        return str_replace([':attribute', ':param', ':diff'], [$attribute, $parameters[0], $parameters[1]], $message);
    }
}