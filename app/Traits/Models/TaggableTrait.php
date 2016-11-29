<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.02.16
 * Time: 19:31
 */

namespace App\Traits\Models;

use App\Models\Tagged;

/**
 * Class TaggableTrait
 * @package App\Models\Traits
 */
trait TaggableTrait
{
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags()
    {
        return $this->morphMany(Tagged::class, 'taggable')->with('tag');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinTags($query)
    {
        return $query->leftJoin('tagged', 'tagged.taggable_id', '=', self::getTable().'.id')
            ->whereRaw('taggable_type = \''.str_replace('\\', '\\\\', self::class).'\'')
            ->leftJoin('tags', 'tags.id', '=', 'tagged.tag_id');
    }
    
    /**
     * @param string $implode
     *
     * @return string
     */
    public function tagsList($implode = ', ')
    {
        $tags = '';
        
        foreach ($this->tags as $tag) {
            $tags .= $tag->tag->name.$implode;
        }
        
        return trim($tags, ', ');
    }
}
