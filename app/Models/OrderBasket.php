<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 12.09.16
 * Time: 2:35
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderBasket
 * @package App\Models
 */
class OrderBasket extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'weekly_menu_basket_id',
        'basket_id',
        'name',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weekly_menu_basket()
    {
        return $this->belongsTo(WeeklyMenuBasket::class, 'weekly_menu_basket_id')->with('basket');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class, 'basket_id');
    }
    
    /**
     * @param int|float $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) ($value * 100);
    }
    
    /**
     * @param int $value
     *
     * @return float
     */
    public function getPriceAttribute($value)
    {
        return $value / 100;
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeMain($query)
    {
        return $query->whereNull('basket_id')->whereNotNull('weekly_menu_basket_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeAdditional($query)
    {
        return $query->whereNotNull('basket_id')->whereNull('weekly_menu_basket_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinOrder($query)
    {
        return $query->leftJoin('orders', 'orders.id', '=', 'order_baskets.order_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBasket($query)
    {
        return $query->leftJoin('baskets', 'baskets.id', '=', 'order_baskets.basket_id');
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return empty($this->weekly_menu_basket_id) ? $this->basket->getName() : $this->weekly_menu_basket->getName();
    }
    
    /**
     * @param int $recipes
     *
     * @return int
     */
    public function getPlaces($recipes = 0)
    {
        if (empty($this->weekly_menu_basket_id)) {
            return $this->basket->getPlaces();
        }
        
        return $this->weekly_menu_basket->getPlaces(null, $recipes);
    }
}