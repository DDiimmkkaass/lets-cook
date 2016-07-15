<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.16
 * Time: 17:06
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * @package App\Models
 */
class City extends Model
{
    
    use PositionSortedTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];
}