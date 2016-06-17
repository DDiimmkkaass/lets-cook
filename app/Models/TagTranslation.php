<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:38
 */

namespace App\Models;

use Eloquent;

/**
 * Class TagTranslation
 * @package App\Models
 */
class TagTranslation extends Eloquent
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $fillable = [
        'locale',
        'name',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];
}