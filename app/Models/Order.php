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
        'coupon_id',
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
        'deleted',
        'archived',
    ];
    
    /**
     * @var array
     */
    protected static $editable_statuses_user = [
        'changed',
        'paid',
        'processed',
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
    public function main_basket()
    {
        return $this->hasOne(OrderBasket::class, 'order_id')->with('weekly_menu_basket')->main();
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
    public function additional_baskets()
    {
        return $this->hasMany(OrderBasket::class, 'order_id')->with('basket')->additional();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany(OrderIngredient::class)->with('ingredient');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(OrderComment::class)->with('user');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
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
    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = $value * 100;
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
     * @param int $value
     */
    public function setCouponIdAttribute($value)
    {
        $this->attributes['coupon_id'] = empty($value) ? null : (int) $value;
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
    public function getSubtotalAttribute($value)
    {
        return $value / 100;
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
        $dt = Carbon::create($year, 1, 1)->addWeeks($week)->startOfWeek();
        
        $to = clone($dt->endOfDay());
        $from = $dt->subDay()->startOfDay();
        
        return $query->where('delivery_date', '>=', $from)->where('delivery_date', '<=', $to);
    }
    
    /**
     * @param $query
     */
    public function scopeForCurrentWeek($query)
    {
        $dt = active_week();
        
        return $query->forWeek($dt->year, $dt->weekOfYear);
    }
    
    /**
     * @param $query
     */
    public function scopeJoinOrderBaskets($query)
    {
        return $query->leftJoin('order_baskets', 'order_baskets.order_id', '=', 'orders.id');
    }
    
    /**
     * @param $query
     */
    public function scopeJoinWeeklyMenuBasket($query)
    {
        return $query->leftJoin(
            'weekly_menu_baskets',
            'weekly_menu_baskets.id',
            '=',
            'order_baskets.weekly_menu_basket_id'
        );
    }
    
    /**
     * @return bool
     */
    public function forCurrentWeek()
    {
        $dt = active_week();
        
        $to = $dt->endOfDay()->format('d-m-Y');
        $from = $dt->subDay()->startOfDay()->format('d-m-Y');
        
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
     * @return int
     */
    public function getPortions()
    {
        return $this->main_basket->getPortions();
    }
    
    /**
     * @return int
     */
    public function getPlacesCount()
    {
        $places = 0;
        
        $recipes = $this->recipes->count();
        
        $places += $this->main_basket->getPlaces($recipes);
        
        foreach ($this->additional_baskets as $basket) {
            $places += $basket->getPlaces();
        }
        
        return $places;
    }
    
    /**
     * @param bool $with_portions
     *
     * @return string
     */
    public function getMainBasketName($with_portions = false)
    {
        return $this->main_basket->name.' '.($with_portions ? $this->main_basket->weekly_menu_basket->portions : '');
    }
    
    /**
     * @param string $split
     *
     * @return string
     */
    public function getAdditionalBasketsList($split = ', ')
    {
        $list = [];
        
        $this->additional_baskets->each(
            function ($item) use (&$list) {
                $list[] = $item->getName();
            }
        );
        
        return implode($split, $list);
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
     * @return string
     */
    public function getFormattedDeliveryDate()
    {
        return $this->getDeliveryDate()->format('d').' '.
        get_localized_date($this->delivery_date, 'd-m-Y', false, '', '%f').', '.
        day_of_week($this->delivery_date, 'd-m-Y').', '.
        $this->delivery_time;
    }
    
    /**
     * @return string
     */
    public function getCouponCode()
    {
        return empty($this->coupon_id) ? '-' : $this->coupon->code;
    }
    
    /**
     * @return int|float
     */
    public function getDiscount()
    {
        return $this->subtotal - $this->total;
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