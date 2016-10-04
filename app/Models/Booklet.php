<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.10.16
 * Time: 16:03
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Booklet
 * @package App\Models
 */
class Booklet extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'year',
        'week',
        'link',
    ];
    
    /**
     * @param $query
     * @param int $year
     * @param int $week
     *
     * @return mixed
     */
    public function scopeForWeek($query, $year, $week)
    {
        return $query->where($this->getTable().'.year', $year)->where($this->getTable().'.week', $week);
    }
}