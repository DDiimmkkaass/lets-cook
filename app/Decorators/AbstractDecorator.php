<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.10.15
 * Time: 14:36
 */

namespace App\Decorators;

/**
 * Class AbstractDecorator
 * @package App\Decorators
 */
abstract class AbstractDecorator
{

    /**
     * @var
     */
    protected $object;

    /**
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDecorated();
    }

    /**
     * @return string
     */
    abstract function getDecorated();
}