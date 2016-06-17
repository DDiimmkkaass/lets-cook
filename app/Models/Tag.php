<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:38
 */

namespace App\Models;

use App\Contracts\FrontLink;
use App\Contracts\SearchableContract;
use App\Traits\Models\SearchableTrait;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Eloquent;

/**
 * Class Tag
 * @package App\Models
 */
class Tag extends Eloquent implements FrontLink, SearchableContract
{

    use Translatable;
    use WithTranslationsTrait;
    use SearchableTrait;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'status',
        'position',
        'name',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->hasMany(Tagged::class, 'tag_id', 'id');
    }

    /**
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = empty($value) ? str_slug($this->attributes['name']) : $value;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus(true);
    }

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePositionSorted($query, $order = 'ASC')
    {
        return $query->orderBy('position', $order);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        // TODO: Implement getUrl() method.
    }

    /**
     * @return string
     */
    public function getImageForSearchIndex()
    {
        return '';
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getTitleForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->name : $this->name;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getDescriptionForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_description : $this->meta_description;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaTitleForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_title : $this->meta_title;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaDescriptionForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_description : $this->meta_description;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaKeywordsForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_keywords : $this->meta_keywords;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        // TODO: Implement getBreadcrumbs() method.
    }
}