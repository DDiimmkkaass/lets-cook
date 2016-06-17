<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.06.15
 * Time: 15:42
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuTranslation
 * @package App\Models
 */
class MenuTranslation extends Model
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['title'];
}