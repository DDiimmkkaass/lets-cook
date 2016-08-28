<?php

namespace App\Models;

use App\Contracts\FrontLink;
use App\Traits\Models\FieldableTrait;
use Carbon;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;

/**
 * Class User
 * @package App\Models
 */
class User extends SentryUser implements FrontLink
{
    
    use FieldableTrait;
    
    /**
     * @var array
     */
    protected $with = ['info'];
    
    /**
     * @var string
     */
    protected $table = "users";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function info()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_groups', 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_comments()
    {
        return $this->hasMany(OrderComment::class);
    }
    
    /**
     * @param      $q
     */
    public function scopeJoinInfo($q)
    {
        return $q->leftJoin('user_info', 'users.id', '=', 'user_info.user_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->whereActivated(true);
    }
    
    /**
     * @param string $persistCode
     *
     * @return bool
     */
    public function checkPersistCode($persistCode)
    {
        return true;
    }
    
    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (empty($this->info->full_name)) {
            return '';
        }
        
        return $this->info->full_name;
    }
    
    /**
     * @return string
     */
    public function getPhoneAttribute()
    {
        if (empty($this->info->phone)) {
            return '';
        }
        
        return $this->info->phone;
    }
    
    /**
     * @return string
     */
    public function getBirthdayAttribute()
    {
        if (empty($this->info->birthday)) {
            return '';
        }
        
        return $this->info->birthday;
    }
    
    /**
     * @return string
     */
    public function getGenderAttribute()
    {
        if (empty($this->info->gender)) {
            return null;
        }
        
        return $this->info->gender;
    }
    
    /**
     * @return string
     */
    public function getAvatarAttribute()
    {
        if (empty($this->info->avatar)) {
            return config('user.no_image');
        }
        
        return $this->info->avatar;
    }
    
    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }
    
    /**
     * @return bool
     */
    public function exist()
    {
        return !empty($this->id);
    }
    
    /**
     * update user last login
     */
    public function updateActivity()
    {
        $this->ip_address = request()->getClientIp();
        $this->last_activity = Carbon::now()->toDateTimeString();
        
        $this->save();
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return localize_route('profiles.show', $this->id);
    }
}
