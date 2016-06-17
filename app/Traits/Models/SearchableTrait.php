<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.03.16
 * Time: 17:27
 */

namespace App\Traits\Models;

use App\Models\SearchIndex;

/**
 * Class SearchableTrait
 * @package App\Traits\Models
 */
trait SearchableTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function search_indexes()
    {
        return $this->morphMany(SearchIndex::class, 'searchable');
    }
}