<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 17:10
 */

namespace App\Traits\Models;

/**
 * Class VisibleTrait
 * @package App\Models\Traits
 */
trait VisibleTrait
{

    /**
     * @param        $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus('true');
    }
}
