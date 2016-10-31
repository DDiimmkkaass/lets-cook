<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 25.09.16
 * Time: 1:51
 */

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserCoupon
 * @package App\Models
 */
class UserCoupon extends Model
{
    
    /**
     * @var array
     */
    protected $with = ['coupon'];
    
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'coupon_id',
        'default',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'coupon_id', 'coupon_id');
    }
    
    /**
     * @param User|null $user
     * @param bool      $full_message
     *
     * @return bool|string
     */
    public function available($user, $full_message = false)
    {
        $now = Carbon::now();
        
        if ($this->getExpiredAt() && $this->getExpiredAt() < $now) {
            $message = trans('front_messages.time of this coupon out');
        }
        
        if ($this->getStartedAt() && $this->getStartedAt() >= $now) {
            $message = trans('front_messages.this coupon has not yet started');
        }
        
        if ($this->getAvailableCount() <= 0) {
            $message = trans('front_messages.this coupon already used');
        }
        
        if ($this->getUsersType() == 'new') {
            if ($user && ($user->orders->count() > 0 || (int) $user->old_site_orders_count > 0)) {
                $message = trans('front_messages.coupon available only for new users');
            }
        }
        
        if ($this->getUsersType() == 'exists') {
            if (!$user || ($user->orders->count() == 0 && (int) $user->old_site_orders_count == 0)) {
                $message = trans('front_messages.coupon available only for exists users');
            }
        }
        
        if ($full_message) {
            return isset($message) ? $message : true;
        }
        
        return isset($message) ? false : true;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->coupon->name;
    }
    
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->coupon->code;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->coupon->getStringType();
    }
    
    /**
     * @return string
     */
    public function getUsersType()
    {
        return $this->coupon->getStringUsersType();
    }
    
    /**
     * @return int
     */
    public function getCouponsCount()
    {
        return $this->coupon->count;
    }
    
    /**
     * @return int|float
     */
    public function getDiscount()
    {
        return $this->coupon->discount;
    }
    
    /**
     * @return int|float
     */
    public function getMainDiscount()
    {
        return in_array($this->getType(), ['main', 'all']) ? $this->coupon->discount : 0;
    }
    
    /**
     * @return int|float
     */
    public function getAdditionalDiscount()
    {
        return in_array($this->coupon->getType(), ['additional', 'all']) ? $this->coupon->discount : 0;
    }
    
    /**
     * @return string
     */
    public function getDiscountType()
    {
        return $this->coupon->getStringDiscountType();
    }
    
    /**
     * @return string
     */
    public function getStartedAt()
    {
        return $this->coupon->getStartedAt();
    }
    
    /**
     * @return string
     */
    public function getExpiredAt()
    {
        return $this->coupon->getExpiredAt();
    }
    
    /**
     * @return int
     */
    public function getAvailableCount()
    {
        $coupons_count = $this->getCouponsCount();
        
        $available_count = $coupons_count - $this->orders->count();
        
        return $coupons_count > 0 ? ($available_count <= 0 ? 0 : $available_count) : 1;
    }
    
    /**
     * @return string|int
     */
    public function getAvailableCountLabel()
    {
        return $this->getCouponsCount() == 0 ? trans('front_labels.unlimited') : $this->getAvailableCount();
    }
    
    /**
     * @return string
     */
    public function getDiscountLabel()
    {
        $label = '';
        
        $type = $this->coupon->getStringType();
        
        if ($type == 'main' || $type == 'all') {
            $label .= trans('front_labels.main_short').': '.
                $this->getDiscount().' '.
                $this->getDiscountTypeLabel().
                '<br>';
        }
        
        if ($type == 'additional' || $type == 'all') {
            $label .= trans('front_labels.additional_short').': '.
                $this->getDiscount().' '.
                $this->getDiscountTypeLabel();
        }
        
        return $label;
    }
    
    /**
     * @return string
     */
    public function getDiscountTypeLabel()
    {
        return $this->coupon->getStringDiscountType() == 'absolute' ? currency() : '%';
    }
}