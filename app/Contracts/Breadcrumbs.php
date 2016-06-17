<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 16.06.15
 * Time: 17:08
 */

namespace App\Contracts;

/**
 * Interface Breadcrumbs
 * @package App\Contracts
 */
interface Breadcrumbs
{

    /**
     * @param object|null $model
     */
    public function setBreadcrumbs($model);
}