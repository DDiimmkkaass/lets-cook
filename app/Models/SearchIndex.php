<?php namespace App\Models;

use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Lang;
use Nicolaslopezj\Searchable\SearchableTrait;

/**
 * Class SearchIndex
 * @package App\Models
 */
class SearchIndex extends Model
{
    use Translatable;
    use WithTranslationsTrait;
    use SearchableTrait;

    /**
     * @var string
     */
    protected $table = 'search_indexes';

    /**
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'searchable_id',
        'searchable_type',
        'image',
        'title',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'search_index_translations.title'            => 100,
            'search_index_translations.description'      => 50,
            'search_index_translations.meta_keywords'    => 10,
            'search_index_translations.meta_title'       => 8,
            'search_index_translations.meta_description' => 6,
        ],
        'joins'   => [
            'search_index_translations' => [
                'search_indexes.id', 'search_index_translations.search_index_id'
            ],
        ],
    ];

    /**
     * @var null|Model
     */
    public $parent = null;

    /**
     * SearchIndex constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->searchable['joins']['search_index_translations'][] = 'search_index_translations.locale';
        $this->searchable['joins']['search_index_translations'][] = Lang::getLocale();
    }

    /**
     *
     * /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function searchable()
    {
        return $this->morphTo();
    }

    /**
     * @param        $query
     * @param string $type
     *
     * @return mixed
     * @throws
     */
    public function scopeOf($query, $type = null)
    {
        if (!$type) {
            return $query;
        }

        if (!class_exists('\\'.$type)) {
            $type = 'App\\\\Models\\\\'.studly_case($type);
        } else {
            $type = str_replace('\\', '\\\\', $type);
        }

        return $query->whereRaw('searchable_type = \''.$type.'\'');
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            Page::class,
            News::class,
            Question::class,
            Tag::class,
            Comment::class,
        ];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getParent()->getUrl();
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->getParent()->getBreadcrumbs();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getShortContent()
    {
        return str_limit(
            strip_tags($this->description),
            config('search.default_short_content_length')
        );
    }

    /**
     * @return Model
     */
    public function getParent()
    {
        if (!$this->parent) {
            $parentModel = $this->searchable_type;

            $this->parent = $parentModel::whereId($this->searchable_id)->first();
        }

        return $this->parent;
    }
}
