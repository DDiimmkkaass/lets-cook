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
    public function scopeJoinOrder($query)
    {
        return $query->leftJoin('orders', 'orders.id', '=', 'order_recipes.order_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBasketRecipe($query)
    {
        return $query->leftJoin('basket_recipes', 'basket_recipes.id', '=', 'order_recipes.basket_recipe_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinWeeklyMenuBasket($query)
    {
        return $query->leftJoin(
            'weekly_menu_baskets',
            'weekly_menu_baskets.id',
            '=',
            'basket_recipes.weekly_menu_basket_id'
        );
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBasket($query)
    {
        return $query->leftJoin('baskets', 'baskets.id', '=', 'weekly_menu_baskets.basket_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinRecipe($query)
    {
        return $query->leftJoin('recipes', 'recipes.id', '=', 'basket_recipes.recipe_id');
    }
    
    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopeDateSorted($query, $order = 'ASC')
    {
        return $query->orderBy('created_at', $order);
    }
}