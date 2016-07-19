<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 15.07.16
 * Time: 17:42
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderRecipe
 * @package App\Models
 */
class OrderRecipe extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'basket_recipe_id',
        'name',
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
    public function recipe()
    {
        return $this->belongsTo(BasketRecipe::class, 'basket_recipe_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBasketRecipes($query)
    {
        return $query->leftJoin('basket_recipes', 'basket_recipes.id', '=', 'order_recipes.basket_recipe_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinRecipes($query)
    {
        return $query->leftJoin('recipes', 'recipes.id', '=', 'basket_recipes.recipe_id');
    }
}