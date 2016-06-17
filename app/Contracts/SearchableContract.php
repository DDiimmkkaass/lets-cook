<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.12.15
 * Time: 15:52
 */

namespace App\Contracts;

/**
 * Interface SearchableContract
 * @package App\Contracts
 */
interface SearchableContract
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function search_indexes();

    /**
     * @return string
     */
    public function getImageForSearchIndex();

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getTitleForSearchIndex($locale = null);

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getDescriptionForSearchIndex($locale = null);

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaTitleForSearchIndex($locale = null);

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaDescriptionForSearchIndex($locale = null);

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaKeywordsForSearchIndex($locale = null);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return array
     */
    public function getBreadcrumbs();

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeVisible($query);
}