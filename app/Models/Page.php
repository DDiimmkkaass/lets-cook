<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Models;

use App\Contracts\FrontLink;
use App\Contracts\MetaGettable;
use App\Contracts\SearchableContract;
use App\Traits\Models\SearchableTrait;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Eloquent;

/**
 * Class Page
 * @package App\Models
 */
class Page extends Eloquent implements FrontLink, SearchableContract, MetaGettable
{

    use Translatable;
    use WithTranslationsTrait;
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
        'parent_id',
        'status',
        'position',
        'name',
        'image',
        'short_content',
        'content',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id')->with('parent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(Page::class, 'parent_id');
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
     * @param $value
     */
    public function setParentIdAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['parent_id'] = null;
        } else {
            $this->attributes['parent_id'] = $value;
        }
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->where('status', true);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNoHome($query)
    {
        return $query->where('id', '<>', 1)->where('slug', '<>', 'home');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url[] = $this->slug;

        if ($this->slug == 'home') {
            return localize_url('/');
        }

        $page = $this;

        while ($page->parent) {
            $page = $page->parent;
            $url[] = $page->slug;
        }

        return localize_url(route('pages.show', array_reverse($url)));
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParents()
    {
        $result = [];

        $obj = $this->parent();

        while ($obj->count()) {
            $result[] = $obj->first();

            $obj = $obj->first()->parent();
        }

        return array_reverse($result);
    }

    /**
     * @param int|bool $limit
     *
     * @return string
     */
    public function getShortContent($limit = false)
    {
        $limit = $limit === true ? config('page.default_short_content_length') : $limit;

        $content = empty($this->short_content) ? $this->content : $this->short_content;

        return $limit ? str_limit(strip_tags($content), $limit) : $content;
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
        $img = config('seo.share.'.$this->slug.'.image');

        return url(
            empty($img) ?
                (empty($this->image) ? config('seo.share.default_image') : $this->image)
                : $img
        );
    }
}