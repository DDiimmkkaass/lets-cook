<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.06.15
 * Time: 15:50
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuItemTranslation
 * @package App\Models
 */
class MenuItemTranslation extends Model
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'title', 'description'];
}