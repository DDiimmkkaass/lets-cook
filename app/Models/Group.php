<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Models;

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroup;

/**
 * Class Group
 * @package App\Models
 */
class Group extends SentryGroup
{

    /**
     * @var string
     */
    protected $table = 'groups';

    /**
     * @var array
     */
    protected $fillable = ['name', 'permissions'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {

        return $this->belongsToMany(User::class, 'users_groups', 'user_id');
    }
}