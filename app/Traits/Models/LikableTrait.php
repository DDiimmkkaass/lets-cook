<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.02.16
 * Time: 19:31
 */

namespace App\Traits\Models;

use App\Models\Like;

/**
 * Class LikableTrait
 * @package App\Models\Traits
 */
trait LikableTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }
}
