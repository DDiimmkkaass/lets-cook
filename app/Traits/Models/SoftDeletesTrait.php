<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 20.07.16
 * Time: 13:29
 */

namespace App\Traits\Models;

/**
 * Class SoftDeletesTrait
 * @package App\Models\Traits
 */
trait SoftDeletesTrait
{
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWithoutTrashed($query)
    {
        return $query->whereNull($this->getTable().'.deleted_at');
    }
}
