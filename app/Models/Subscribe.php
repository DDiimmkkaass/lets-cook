<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.02.16
 * Time: 19:31
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscribe
 * @package App\Models
 */
class Subscribe extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'email'
    ];
}
