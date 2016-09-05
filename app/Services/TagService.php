<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 13:09
 */

namespace App\Services;

use App\Models\TagCategory;
use Illuminate\Database\Eloquent\Collection;

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
            ->get(['tag_categories.*']);
        
        return $categories;
    }
}