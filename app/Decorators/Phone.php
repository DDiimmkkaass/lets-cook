<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.10.15
 * Time: 14:36
 */

namespace App\Decorators;

/**
 * Class Phone
 * @package App\Decorators
 */
class Phone extends AbstractDecorator
{

    /**
     * @return string
     */
    function getDecorated()
    {
        $phone = preg_replace('/([^0-9]+)/', '', $this->object);

        if (str_is('380*', $phone)) {
            // nothing to do
        } elseif (str_is('80*', $phone)) {
            $phone = '3'.$phone;
        } elseif (str_is('0*', $phone)) {
            $phone = '38'.$phone;
        } elseif (strlen($phone) == 9) {
            $phone = '380'.$phone;
        }

        return $phone;
    }
}