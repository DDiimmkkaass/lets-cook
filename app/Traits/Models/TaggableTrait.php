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
}
