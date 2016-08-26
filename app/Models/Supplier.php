<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.16
 * Time: 14:52
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Supplier
 * @package App\Models
 */
class Supplier extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'comments',
        'priority',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
    
    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePrioritySorted($query, $order = 'ASC')
    {
        return $query->orderBy('priority', $order);
    }
}