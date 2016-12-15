<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:38
 */

namespace App\Models;

use App\Traits\Models\VisibleTrait;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package App\Models
 */
class Tag extends Model
{
    
    use Translatable;
    use WithTranslationsTrait;
    
    /**
     * @var array
     */
    protected $with = ['translations'];
    
    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'image',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function taggable()
    {
        return $this->hasMany(Tagged::class, 'tag_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(TagCategory::class);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinTagCategory($query)
    {
        return $query->leftJoin('tag_categories', 'tag_categories.id', '=', 'tags.category_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinTagged($query)
    {
        return $query->join('tagged', 'tagged.tag_id', '=', 'tags.id');
    }
}