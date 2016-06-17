<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.03.16
 * Time: 14:30
 */

namespace App\Models;

use App\Contracts\MetaGettable;
use App\Contracts\SearchableContract;
use App\Traits\Models\SearchableTrait;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * @package App\Models
 */
class Question extends Model implements SearchableContract, MetaGettable
{

    use Translatable;
    use WithTranslationsTrait;
    use SearchableTrait;

    /**
     * @var array
     */
    public $translatedAttributes = ['question', 'answer'];

    /**
     * @var array
     */
    protected $fillable = ['question', 'answer', 'status', 'position'];

    /**
     * @return string
     */
    public function getImageForSearchIndex()
    {
        // TODO: Implement getImageForSearchIndex() method.
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getTitleForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->question : $this->question;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getDescriptionForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->answer : $this->answer;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaTitleForSearchIndex($locale = null)
    {
        return '';
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaDescriptionForSearchIndex($locale = null)
    {
        return '';
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaKeywordsForSearchIndex($locale = null)
    {
        return '';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return localize_url(route('questions.index').'#question_'.$this->id);
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        // TODO: Implement getBreadcrumbs() method.
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus(true);
    }

    /**
     * @param mixed  $query
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
    public function getMetaTitle()
    {
        return $this->question;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return str_limit(
            empty($this->answer) ? strip_tags($this->question) : strip_tags($this->answer),
            config('seo.share.meta_description_length'));
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getMetaImage()
    {
        return '';
    }
}