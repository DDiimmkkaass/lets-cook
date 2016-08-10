<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 14.07.16
 * Time: 16:34
 */

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App\Models
 */
class Order extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'subscribe_period',
        'status',
        'full_name',
        'email',
        'phone',
        'additional_phone',
        'verify_call',
        'delivery_date',
        'delivery_time',
        'city_id',
        'city',
        'address',
        'comment',
    ];
    
    /**
     * @var array
     */
    protected $dates = ['delivery_date'];
    
    /**
     * @var array
     */
    protected static $types = [
        1 => 'single',
        2 => 'subscribe',
    ];
    
    /**
     * @var array
     */
    protected static $subscribe_periods = [1, 2];
    
    /**
     * @var array
     */
    protected static $statuses = [
        0 => 'new',
        1 => 'payed',
        2 => 'canceled',
        3 => 'edited',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes()
    {
        return $this->hasMany(OrderRecipe::class)->with('recipe');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany(OrderIngredient::class)->with('ingredient');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function baskets()
    {
        return $this->belongsToMany(Basket::class);
    }
    
    /**
     * @param $value
     */
    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = $value * 100;
    }
    
    /**
     * @param int $value
     */
    public function setCityIdAttribute($value)
    {
        $this->attributes['city_id'] = empty($value) ? null : (int) $value;
    }
    
    /**
     * @param string $value
     */
    public function setCityAttribute($value)
    {
        $this->attributes['city'] = empty($value) ? null : (string) $value;
    }
    
    /**
     * @param string $value
     *
     * @return float
     */
    public function setDeliveryDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['delivery_date'] = null;
        } else {
            $this->attributes['delivery_date'] = Carbon::createFromFormat('d-m-Y', $value)
                ->startOfDay()->format('Y-m-d H:i:s');
        }
    }
    
    /**
     * @param $value
     *
     * @return float
     */
    public function getTotalAttribute($value)
    {
        return $value / 100;
    }
    
    /**
     * @param $value
     *
     * @return float
     */
    public function getDeliveryDateAttribute($value)
    {
        return empty($value) ? '' : Carbon::createFromFormat('Y-m-d H:i:s', $value)->startOfDay()->format('d-m-Y');
    }
    
    /**
     * @return string
     */
    public function getUserFullName()
    {
        return $this->full_name;
    }
    
    /**
     * @param        $query
     * @param string $type
     *
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->whereType(self::getTypeIdByName($type));
    }
    
    /**
     * @param        $query
     * @param string $status
     *
     * @return mixed
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->whereStatus(self::getStatusIdByName($status));
    }
    
    /**
     * @param $query
     */
    public function scopeForNextWeek($query)
    {
        return $query->where('delivery_date', '>=', Carbon::now()->addWeek()->startOfWeek())
            ->where('delivery_date', '<=', Carbon::now()->addWeek()->endOfWeek());
    }
    
    /**
     * @return bool
     */
    public function isSubscribe()
    {
        return $this->type == self::getTypeIdByName('subscribe');
    }
    
    /**
     * @return float
     */
    public function getTotal()
    {
        $total = 0;
        
        $total += $this->baskets()->sum('price') / 100;
        
        $total += $this->ingredients()->get()->reduce(
            function ($_total, $item) {
                return $_total + ($item->ingredient->sale_price * $item->count);
            }
        );
        
        return $total;
    }
    
    /**
     * @return array
     */
    public static function getTypes()
    {
        return self::$types;
    }
    
    /**
     * @return array
     */
    public static function getSubscribePeriods()
    {
        return self::$subscribe_periods;
    }
    
    /**
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }
    
    /**
     * @param string $type
     *
     * @return int|null
     */
    public static function getTypeIdByName($type)
    {
        foreach (self::$types as $id => $_type) {
            if ($_type == $type) {
                return $id;
            }
        }
        
        return null;
    }
    
    /**
     * @param string $status
     *
     * @return int|null
     */
    public static function getStatusIdByName($status)
    {
        foreach (self::$statuses as $id => $_status) {
            if ($_status == $status) {
                return $id;
            }
        }
        
        return null;
    }
    
    /**
     * @return string
     */
    public function getStringType()
    {
        foreach (self::$types as $id => $type) {
            if ($id == $this->type) {
                return $type;
            }
        }
        
        return '';
    }
    
    /**
     * @return string
     */
    public function getStringStatus()
    {
        foreach (self::$statuses as $id => $status) {
            if ($id == $this->status) {
                return $status;
            }
        }
        
        return '';
    }
}