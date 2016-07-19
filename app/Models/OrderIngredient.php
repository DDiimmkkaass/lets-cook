<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 15.07.16
 * Time: 18:20
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderIngredient
 * @package App\Models
 */
class OrderIngredient extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'ingredient_id',
        'name',
        'count',
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
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class)->withTrashed();
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return empty($this->ingredient->name) ? $this->name : $this->ingredient->name;
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        return empty($this->ingredient->image) ? '' : $this->ingredient->image;
    }
}