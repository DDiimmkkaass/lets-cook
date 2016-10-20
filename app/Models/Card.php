<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 28.09.16
 * Time: 14:22
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 * @package App\Models
 */
class Card extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'number',
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
     * @param     $query
     * @param int $user_id
     *
     * @return mixed
     */
    public function scopeOfUser($query, $user_id)
    {
        return $query->where($this->getTable().'.user_id', $user_id);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeDefault($query)
    {
        return $query->where($this->getTable().'.default', true)->whereNotNull($this->getTable().'invoice_id');
    }
}