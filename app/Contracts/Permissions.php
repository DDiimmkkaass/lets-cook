<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Contracts;

/**
 * Interface Permissions
 * @package App\Contracts
 */
interface Permissions
{

    /**
     * Load permissions list single level array
     *
     * @return array
     */
    public function load();

    /**
     * Load permissions list in multi-dimensional array
     *
     * @return array
     */
    public function loadArray();
}