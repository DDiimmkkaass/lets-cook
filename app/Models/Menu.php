<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.06.15
 * Time: 15:40
 */

namespace App\Models;

use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * @package App\Models
 */
class Menu extends Model
{

    use Translatable;
    use WithTranslationsTrait;

    /**
     * @var array
     */
    public $translatedAttributes = ['name'];

    /**
     * @var array
     */
    protected $fillable = ['layout_position', 'class', 'name', 'template', 'show_title', 'position', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->positionSorted();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visible_items()
    {
        return $this->items()->visible();
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus(true);
    }

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePositionSorted($query, $order = 'ASC')
    {
        return $query->orderBy('position', $order);
    }
}