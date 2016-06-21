<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 12:40
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NutritionalValue
 * @package App\Models
 */
class NutritionalValue extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];
}