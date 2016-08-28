<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.08.16
 * Time: 22:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderComment
 * @package App
 */
class OrderComment extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'comment',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->with('info');
    }
    
    /**
     * @return string
     */
    public function getAdminName()
    {
        if (empty($this->user_id)) {
            return 'System';
        }
        
        return $this->user->getFullName();
    }
}
