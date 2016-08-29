<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 28.08.16
 * Time: 23:34
 */

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Coupon
 * @package App\Models
 */
class Coupon extends Model
{
    
    use SoftDeletes;
    
    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'description',
        'discount',
        'discount_type',
        'count',
        'users_count',
        'started_at',
        'expired_at',
    ];
    
    /**
     * @var array
     */
    protected static $types = [
        'all',
        'main',
        'additional',
    ];
    
    /**
     * @var array
     */
    protected static $discount_types = [
        'absolute',
        'percentage',
    ];
    
    /**
     * @param float $value
     */
    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = $value * 100;
    }
    
    /**
     * @param string $value
     */
    public function setStartedAtAttribute($value)
    {
        $this->attributes['started_at'] = empty($value) ?
            null :
            Carbon::createFromFormat('d-m-Y', $value)->startOfDay()->format('Y-m-d H:i:s');
    }
    
    /**
     * @param string $value
     */
    public function setExpiredAtAttribute($value)
    {
        $this->attributes['expired_at'] = empty($value) ?
            null :
            Carbon::createFromFormat('d-m-Y', $value)->endOfDay()->format('Y-m-d H:i:s');
    }
    
    /**
     * @param int $value
     *
     * @return float
     */
    public function getDiscountAttribute($value)
    {
        return $value / 100;
    }
    
    /**
     * @param $value
     *
     * @return string
     */
    public function getStartedAtAttribute($value)
    {
        return empty($value) ? '' : Carbon::createFromFormat('Y-m-d H:i:s', $value)->startOfDay()->format('d-m-Y');
    }
    
    /**
     * @param $value
     *
     * @return string
     */
    public function getExpiredAtAttribute($value)
    {
        return empty($value) ? '' : Carbon::createFromFormat('Y-m-d H:i:s', $value)->endOfDay()->format('d-m-Y');
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
    public function getStringDiscountType()
    {
        foreach (self::$discount_types as $id => $discount_type) {
            if ($id == $this->discount_type) {
                return $discount_type;
            }
        }
        
        return '';
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
    public static function getDiscountTypes()
    {
        return self::$discount_types;
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
     * @param string $discount_type
     *
     * @return int|null
     */
    public static function getDiscountTypeIdByName($discount_type)
    {
        foreach (self::$discount_types as $id => $_discount_type) {
            if ($_discount_type == $discount_type) {
                return $id;
            }
        }
        
        return null;
    }
}