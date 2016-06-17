<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.02.16
 * Time: 18:27
 */

namespace App\Contracts;

/**
 * Class FrontLink
 * @package App\Contracts
 */
interface FrontLink
{

    /**
     * @return string
     */
    public function getUrl();
}