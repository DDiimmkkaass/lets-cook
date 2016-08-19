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
 * Class AppValidator
 * @package App\Validators
 */
class AppValidator extends Validator
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
     * @param string $attribute
     * @param string $value
     * @param array  $parameters
     *
     * @return bool
     */
    public function validateDeliveryDateDate($attribute, $value, $parameters)
    {
        $delivery_date = Carbon::createFromFormat('d-m-Y', $value)->startOfDay();
        $day_of_week = $delivery_date->dayOfWeek;
        
        if ($day_of_week == 5 || $day_of_week == 6 || $day_of_week == 0) {
            return $delivery_date >= Carbon::now()->startOfDay()->addWeek();
        }
        
        if ($day_of_week == 1) {
            return $delivery_date >= Carbon::now()->startOfDay()->addWeek() ||
            $delivery_date >= Carbon::now()->startOfDay()->addWeek()->subDay();
        };
        
        return true;
    }
    
    /**
     * @param string $attribute
     * @param string $value
     * @param array  $parameters
     *
     * @return bool
     */
    public function validateDeliveryDateDayOfWeek($attribute, $value, $parameters)
    {
        $delivery_date = Carbon::createFromFormat('d-m-Y', $value)->startOfDay();
        
        return $delivery_date->dayOfWeek == 1 || $delivery_date->dayOfWeek == 0;
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