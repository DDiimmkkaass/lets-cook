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
        'name',
        'phone',
        'gender',
        'birthday',
        'avatar',
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
}
