<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:42
 */

namespace App\Models;

use Eloquent;

/**
 * Class BannerTranslation
 * @package App\Models
 */
class BannerTranslation extends Eloquent
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