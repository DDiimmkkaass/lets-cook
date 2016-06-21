<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 12:06
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Parameter
 * @package App\Models
 */
class Parameter extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];
}