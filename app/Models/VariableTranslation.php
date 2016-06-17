<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:48
 */

namespace App\Models;

use Eloquent;

/**
 * Class VariableTranslation
 * @package App\Models
 */
class VariableTranslation extends Eloquent
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['text'];
}