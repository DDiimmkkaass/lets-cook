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
     * @return bool
     */
    public function available()
    {
        return !$this->getExpiredAt() || $this->getExpiredAt() > Carbon::now()->format('Y-m-d H:i:s');
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
     * @return int|float
     */
    public function getDiscount()
    {
        return $this->coupon->discount;
    }
    
    /**
     * @return string
     */
    public function getExpiredAt()
    {
        return $this->coupon->getExpiredAt();
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