<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 17:10
 */

namespace App\Traits\Models;

/**
 * Class PositionSortedTrait
 * @package App\Models\Traits
 */
trait PositionSortedTrait
{

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePositionSorted($query, $order = 'ASC')
    {
        return $query->orderBy($this->getTable().'.position', $order);
    }
}
