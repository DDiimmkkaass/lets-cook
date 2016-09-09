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
        'parent_id',
        'user_id',
        'type',
        'subscribe_period',
        'status',
        'payment_method',
        'full_name',
        'email',
        'phone',
        'additional_phone',
        'verify_call',
        'delivery_date',
        'delivery_time',
        'city_id',
        'city_name',
        'address',
        'comment',
    ];
    
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
        'changed',
        'paid',
        'processed',
        'tmpl',
        'deleted',
        'archived',
    ];
    
    /**
     * @var array
     */
    protected static $editable_statuses_admin = [
        'changed',
        'paid',
        'processed',
        'tmpl',
    ];
    
    /**
     * @var array
     */
    protected static $editable_statuses_user = [
        'changed',
        'paid',
        'tmpl',
    ];
    
    /**
     * @var array
     */
    protected static $payment_methods = [
        'cash',
        'online',
    ];
    
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
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(OrderComment::class)->with('user');
    }
    
    /**
     * @param int|string $value
     */
    public function setStatusAttribute($value)
    {
        if (in_array($value, self::$statuses)) {
            $value = self::getStatusIdByName($value);
        }
        
        $this->attributes['status'] = $value;
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
     */
    public function setDeliveryDateAttribute($value)
    {
        $this->attributes['delivery_date'] = Carbon::createFromFormat('d-m-Y', $value)
            ->startOfDay()->format('Y-m-d H:i:s');
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
     * @return string
     */
    public function getDeliveryDateAttribute($value)
    {
        return empty($value) ? '' : Carbon::createFromFormat('Y-m-d H:i:s', $value)->startOfDay()->format('d-m-Y');
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
        $statuses = [];
        foreach ((array) $status as $value) {
            $statuses[] = self::getStatusIdByName($value);
        }
        
        return $query->whereIn('status', $statuses);
    }
    
    /**
     * @param        $query
     * @param string $status
     *
     * @return mixed
     */
    public function scopeNotOfStatus($query, $status)
    {
        $statuses = [];
        foreach ((array) $status as $value) {
            $statuses[] = self::getStatusIdByName($value);
        }
        
        return $query->whereNotIn('status', $statuses);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeFinished($query)
    {
        return $query->ofStatus('archived');
    }
    
    /**
     * @param     $query
     * @param int $year
     * @param int $week
     *
     * @return
     */
    public function scopeForWeek($query, $year, $week)
    {
        $from = Carbon::create($year, 1, 1)->startOfWeek()->addWeeks($week)->endOfWeek()->startOfDay();
        $to = Carbon::create($year, 1, 1)->startOfWeek()->addWeeks($week)->endOfWeek()->addDay()->endOfDay();
        
        return $query->where('delivery_date', '>=', $from)->where('delivery_date', '<=', $to);
    }
    
    /**
     * @param $query
     */
    public function scopeForCurrentWeek($query)
    {
        $year = Carbon::now()->startOfWeek()->year;
        $week = Carbon::now()->startOfWeek()->weekOfYear;
        
        return $query->forWeek($year, $week);
    }
    
    /**
     * @param $query
     */
    public function scopeJoinAdditionalBaskets($query)
    {
        return $query->leftJoin('basket_order', 'basket_order.order_id', '=', 'orders.id');
    }
    
    /**
     * @return bool
     */
    public function isSubscribe()
    {
        return $this->type == self::getTypeIdByName('subscribe');
    }
    
    /**
     * @return bool
     */
    public function forCurrentWeek()
    {
        $year = Carbon::now()->startOfWeek()->year;
        $week = Carbon::now()->startOfWeek()->weekOfYear;
        
        $from = Carbon::create($year, 1, 1)->startOfWeek()->addWeeks($week)->endOfWeek()->format('d-m-Y');
        $to = Carbon::create($year, 1, 1)->startOfWeek()->addWeeks($week)->endOfWeek()->addDay()->format('d-m-Y');
        
        return $this->delivery_date >= $from && $this->delivery_date <= $to;
    }
    
    /**
     * @param string|null $status
     *
     * @return bool|string
     */
    public function isStatus($status = null)
    {
        $_status = $this->getStringStatus();
        
        if (!$status) {
            return $_status;
        }
        
        return $_status == $status;
    }
    
    /**
     * @param string|null $status
     *
     * @return bool|string
     */
    public function isOriginalStatus($status = null)
    {
        $__status = null;
        
        foreach (self::$statuses as $id => $_status) {
            if ($id == $this->original['status']) {
                $__status = $status;
            }
        }
        
        if (!$status) {
            return $__status;
        }
        
        return $__status == $status;
    }
    
    /**
     * @return float
     */
    public function getTotal()
    {
        $total = 0;
        
        $basket = $this->getMainBasket();
        if ($basket) {
            $days = count($this->recipes);
            
            $total += $basket->getPrice(null, $days);
        }
        
        $total += $this->baskets()->sum('price') / 100;
        
        $total += $this->ingredients()->get()->reduce(
            function ($_total, $item) {
                return $_total + ($item->ingredient->sale_price * $item->count);
            }
        );
        
        return $total;
    }
    
    /**
     * @return WeeklyMenuBasket|null
     */
    public function getMainBasket()
    {
        $recipe = $this->recipes()->first();
        
        if ($recipe) {
            return WeeklyMenuBasket::with('basket')
                ->find($recipe->recipe->weekly_menu_basket_id);
        }
        
        return null;
    }
    
    /**
     * @return int
     */
    public function getPlacesCount()
    {
        $places = 0;
        
        $basket = $this->getMainBasket();
        if ($basket) {
            $days = count($this->recipes);
            
            $places += $basket->getPlaces(null, $days);
        }
    
        $places += $this->baskets()->get()->sum(function ($item) {
            return $item->places;
        });
        
        return $places;
    }
    
    /**
     * @param bool $with_portions
     *
     * @return string
     */
    public function getMainBasketName($with_portions = false)
    {
        $basket = $this->getMainBasket();
        
        if (!$basket) {
            return '';
        }
        
        return $basket->getName().' '.($with_portions ? $basket->portions : '');
    }
    
    /**
     * @return string
     */
    public function getUserFullName()
    {
        return $this->full_name;
    }
    
    /**
     * @return string
     */
    public function getFullAddress()
    {
        $address = trans('labels.city_short').' ';
        
        if (empty($this->city_id)) {
            $address .= $this->city_name;
        } else {
            $address .= $this->city->name;
        }
        
        return trim($address.' '.$this->address);
    }
    
    /**
     * @param string $delimiter
     *
     * @return string
     */
    public function getPhones($delimiter = '<br>')
    {
        return $this->phone.(empty($this->additional_phone) ? '' : $delimiter).$this->additional_phone;
    }
    
    /**
     * @return \Carbon
     */
    public function getDeliveryDate()
    {
        return empty($this->delivery_date) ? '' : Carbon::createFromFormat('d-m-Y', $this->delivery_date)->startOfDay();
    }
    
    /**
     * @param string $permissions
     *
     * @return bool
     */
    public function editable($permissions = 'user')
    {
        if ($permissions == 'user') {
            return in_array($this->getStringStatus(), self::$editable_statuses_user);
        }
        
        return in_array($this->getStringStatus(), self::$editable_statuses_admin);
    }
    
    /**
     * @return bool
     */
    public function canBePaidOnline()
    {
        return $this->getStringPaymentMethod() == 'online';
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
     * @return array
     */
    public static function getPaymentMethods()
    {
        return self::$payment_methods;
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
     * @param string $payment_method
     *
     * @return int|null
     */
    public static function getPaymentMethodIdByName($payment_method)
    {
        foreach (self::$payment_methods as $id => $_payment_method) {
            if ($_payment_method == $payment_method) {
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
    
    /**
     * @return string
     */
    public function getStringPaymentMethod()
    {
        foreach (self::$payment_methods as $id => $payment_method) {
            if ($id == $this->payment_method) {
                return $payment_method;
            }
        }
        
        return '';
    }
    
    /**
     * @param string $payment_method
     *
     * @return bool
     */
    public function paymentMethod($payment_method)
    {
        return $this->payment_method == self::getPaymentMethodIdByName($payment_method);
    }
}