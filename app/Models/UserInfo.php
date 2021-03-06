<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Models;

use Carbon;
use Eloquent;

/**
 * Class UserInfo
 * @package App\Models
 */
class UserInfo extends Eloquent
{
    
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'user_info';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'additional_phone',
        'gender',
        'birthday',
        'avatar',
        'city_id',
        'city_name',
        'address',
        'comment',
        'source',
    ];

    /**
     * @var array
     */
    public static $genders = ['male', 'female'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
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
     * @param string $value
     *
     * @return string
     */
    public function setBirthdayAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['birthday'] = null;
        } else {
            $this->attributes['birthday'] = Carbon::createFromFormat('d-m-Y', $value)->startOfDay()->format('Y-m-d');
        }
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
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = prepare_phone($value);
    }
    
    /**
     * @param string $value
     */
    public function setAdditionalPhoneAttribute($value)
    {
        $this->attributes['additional_phone'] = prepare_phone($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getBirthdayAttribute($value)
    {
        if (empty($value) || $value == '0000-00-00') {
            return null;
        } else {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay()->format('d-m-Y');
        }
    }
    
    /**
     * @return string
     */
    public function getCityName()
    {
        return $this->city_id ? $this->city->name : $this->city_name;
    }
    
    /**
     * @return string
     */
    public function getFullAddress()
    {
        $address = '';
        
        if (empty($this->city_id)) {
            $address .= $this->city_name;
        } else {
            $address .= $this->city->name;
        }
        
        $address = trim($address.' '.$this->address);
        
        return $address ? trans('labels.city_short').' '.$address : '';
    }
}
