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
        $this->requireParameterCount(2, $parameters, 'diff_in_days');
        
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
        $this->requireParameterCount(2, $parameters, 'delivery_date_date');
        
        $now = Carbon::now();
        $year = $parameters[0];
        $week = $parameters[1];
    
        $delivery_date = Carbon::createFromFormat('d-m-Y', $value)->startOfDay();
        
        if (before_week_closing($year, $week)) {
            return $delivery_date > $now->startOfDay();
        }
        
        return $delivery_date >= $now->addWeek()->startOfDay();
    }
    
    /**
     * @param string $attribute
     * @param string $value
     * @param array  $parameters
     *
     * @return bool
     */
    public function validateMaxDeliveryDateDate($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'max_delivery_date_date');
        
        $dt = Carbon::now()->startOfWeek();
        $year = $parameters[0];
        $week = $parameters[1];
        
        if ($year > $dt->year || $week > $dt->weekOfYear) {
            $dt->addWeek();
        }
        
        $max_date = $dt->endOfWeek()->addDay()->addWeek()->endOfDay();
        
        $delivery_date = Carbon::createFromFormat('d-m-Y', $value)->startOfDay();
        
        return $delivery_date <= $max_date;
    }
    
    /**
     * @param string $attribute
     * @param string $value
     * @param array  $parameters
     *
     * @return bool
     */
    public function validateMinDeliveryDateDate($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'min_delivery_date_date');
        
        $dt = Carbon::now()->startOfWeek();
        $year = $parameters[0];
        $week = $parameters[1];
        
        if ($year > $dt->year || $week > $dt->weekOfYear) {
            $dt->addWeek();
        }
        
        $delivery_date = Carbon::createFromFormat('d-m-Y', $value)->startOfDay();
        
        return $delivery_date > $dt;
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
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array  $parameters
     *
     * @return string
     */
    public function replaceDiffInDays($message, $attribute, $rule, $parameters)
    {
        return str_replace([':attribute', ':param', ':diff'], [$attribute, $parameters[0], $parameters[1]], $message);
    }
}