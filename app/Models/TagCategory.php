<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.16
 * Time: 14:52
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use App\Traits\Models\VisibleTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TagCategory
 * @package App\Models
 */
class TagCategory extends Model
{
    
    use VisibleTrait;
    use PositionSortedTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'position',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinTags($query)
    {
        return $query->join('tags', 'tags.category_id', '=', 'tag_categories.id');
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
    
    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopeNameSorted($query, $order = 'ASC')
    {
        return $query->orderBy('name', $order);
    }
}