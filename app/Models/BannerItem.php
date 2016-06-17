<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:43
 */

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BannerItem
 * @package App\Models
 */
class BannerItem extends Model
{

    use Translatable;

    /**
     * @var array
     */
    public $translatedAttributes = ['title', 'text'];

    /**
     * @var array
     */
    protected $fillable = [
        'banner_id',
        'image',
        'title',
        'text',
        'link',
        'status',
        'position',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Banner::class, 'banner_id');
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

    /**
     * @return string
     */
    public function getUrl()
    {
        return localize_url(url($this->attributes['link']));
    }
}