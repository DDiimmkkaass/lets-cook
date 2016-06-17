<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:48
 */

namespace App\Models;

use App\Contracts\FrontLink;
use App\Contracts\MetaGettable;
use App\Contracts\SearchableContract;
use App\Traits\Models\SearchableTrait;
use App\Traits\Models\TaggableTrait;
use App\Traits\Models\WithTranslationsTrait;
use Carbon;
use Dimsav\Translatable\Translatable;
use Eloquent;

/**
 * Class News
 * @package App\Models
 */
class News extends Eloquent implements FrontLink, SearchableContract, MetaGettable
{

    use Translatable;
    use WithTranslationsTrait;
    use TaggableTrait;
    use SearchableTrait;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'short_content',
        'content',
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
        'image',
        'short_content',
        'content',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'publish_at',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getDates()
    {
        return array_merge(parent::getDates(), ['publish_at']);
    }

    /**
     * @param $value
     */
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $value = $this->attributes['name'];
        }

        $this->attributes['slug'] = str_slug($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function setPublishAtAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['publish_at'] = Carbon::now();
        } else {
            $this->attributes['publish_at'] = Carbon::createFromFormat('d-m-Y', $value)->startOfDay()
                ->format('Y-m-d H:i:s');
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getPublishAtAttribute($value)
    {
        if (empty($value) || $value == '0000-00-00 00:00:00') {
            return null;
        } else {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d-m-Y');
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return localize_url(route('news.show', $this->slug));
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->where('status', true)->whereRaw('publish_at <= NOW()');
    }

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePublishAtSorted($query, $order = 'DESC')
    {
        return $query->orderBy('publish_at', $order);
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
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopeDateSorted($query, $order = 'ASC')
    {
        return $query->orderBy('created_at', $order);
    }

    /**
     * @return string
     */
    public function getShortContent()
    {
        return str_limit(
            strip_tags(
                empty($this->short_content) ?
                    $this->content :
                    $this->short_content
            ),
            config('news.default_short_content_length')
        );
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return empty($this->content) ? $this->short_content : $this->content;
    }

    /**
     * @return string
     */
    public function getImageForSearchIndex()
    {
        return $this->image;
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
        return $locale ?
            $this->translate($locale)->short_content.' '.$this->translate($locale)->content :
            $this->short_content.' '.$this->content;
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

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return empty($this->meta_title) ? $this->name : $this->meta_title;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return str_limit(
            empty($this->meta_description) ? strip_tags($this->getContent()) : $this->meta_description,
            config('seo.share.meta_description_length')
        );
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * @return string
     */
    public function getMetaImage()
    {
        return url(
            empty($this->image) ? config('seo.share.default_image') : $this->image
        );
    }
}