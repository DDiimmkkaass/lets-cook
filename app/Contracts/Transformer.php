<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:37
 */

namespace App\Contracts;

/**
 * Interface Transformer
 * @package App\Contracts
 */
interface Transformer
{

    /**
     * @param $model
     *
     * @return array
     */
    public static function transform($model);
}