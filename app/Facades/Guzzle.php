<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.09.16
 * Time: 16:48
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Guzzle
 * @package App\Facades
 */
class Guzzle extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'guzzle';
    }
}