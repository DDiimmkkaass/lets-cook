<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 13:09
 */

namespace App\Services;

use App\Models\Tag;
use App\Models\TagCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TagService
 * @package App\Services
 */
class TagService
{
    
    /**
     * @param string $class_name
     *
     * @return array|Collection
     */
    public function tagCategoriesByClass($class_name)
    {
        $categories = TagCategory::joinTags()->joinTagged()
            ->whereRaw('tagged.taggable_type = \''.str_replace('\\', '\\\\', $class_name).'\'')
            ->groupBy('tag_categories.id')
            ->positionSorted()
            ->nameSorted()
            ->visible()
            ->get(['tag_categories.*']);
        
        return $categories;
    }
    
    /**
     * @param Model $model
     */
    public function tagsForItem(Model $model)
    {
        return Tag::joinTagCategory()->joinTagged()
            ->whereRaw('tagged.taggable_type = \''.'App\\\\Models\\\\'.class_basename($model).'\'')
            ->where('tagged.taggable_id', $model->id)
            ->where('tag_categories.status', true)
            ->get(['tags.id']);
    }
}