<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.06.16
 * Time: 14:43
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Basket
 * @package App\Models
 */
class Basket extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];
}