<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.12.15
 * Time: 15:52
 */

namespace App\Contracts;

/**
 * Interface MetaGettable
 * @package App\Contracts
 */
interface MetaGettable
{

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @return string
     */
    public function getMetaKeywords();

    /**
     * @return string
     */
    public function getMetaImage();

    /**
     * @return string
     */
    public function getUrl();
}