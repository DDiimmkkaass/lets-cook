<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.02.16
 * Time: 16:44
 */

namespace App\Contracts;

/**
 * Interface Commentable
 * @package App\Contracts
 */
interface Commentable
{

    /**
     * @return string
     */
    public function getAdminUrl();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getImage();
}