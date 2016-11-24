<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.11.16
 * Time: 2:36
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use App\Traits\Models\VisibleTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Share
 * @package App\Models
 */
class Share extends Model
{
    
    use PositionSortedTrait;
    use VisibleTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'image',
        'link',
        'position',
        'status',
    ];
}