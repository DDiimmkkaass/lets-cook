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

    use VisibleTrait;
    use Translatable;
    use WithTranslationsTrait;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(TagCategory::class);
    }
}