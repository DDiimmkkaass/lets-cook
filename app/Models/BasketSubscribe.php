<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.09.16
 * Time: 16:36
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BasketSubscribe
 * @package App\Models
 */
class BasketSubscribe extends Model
{
    
    /**
     * @var array
     */
    protected $with = ['additional_baskets'];
    
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'basket_id',
        'subscribe_period',
        'delivery_date',
        'delivery_time',
        'payment_method',
        'portions',
        'recipes',
    ];
    
    /**
     * @var array
     */
    protected static $subscribe_periods = [1, 2];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class, 'basket_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function additional_baskets()
    {
        return $this->belongsToMany(Basket::class, 'basket_subscribe', 'subscribe_id');
    }
    
    /**
     * @return array
     */
    public static function getSubscribePeriods()
    {
        return self::$subscribe_periods;
    }
}