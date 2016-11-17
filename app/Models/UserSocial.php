<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 16.11.16
 * Time: 23:25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSocial
 * @package App\Models
 */
class UserSocial extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'provider',
        'external_id',
        'token',
        'profile_url',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}