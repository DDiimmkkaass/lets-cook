<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.06.16
 * Time: 14:43
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BasketRecipe
 * @package App\Models
 */
class BasketRecipe extends Model
{
    
    use PositionSortedTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'basket_id',
        'weekly_menu_basket_id',
        'recipe_id',
        'main',
        'position',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class)->withTrashed();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class, 'basket_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weekly_menu_basket()
    {
        return $this->belongsTo(WeeklyMenuBasket::class, 'weekly_menu_basket_id')->with('basket');
    }
    
    /**
     * @param int $value
     */
    public function setBasketIdAttribute($value)
    {
        $this->attributes['basket_id'] = empty($value) ? null : (int) $value;
    }
    
    /**
     * @param int $value
     */
    public function setWeeklyMenuBasketIdAttribute($value)
    {
        $this->attributes['weekly_menu_basket_id'] = empty($value) ? null : (int) $value;
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
     * @return string
     */
    public function getName()
    {
        $basket = empty($this->basket_id) ? $this->weekly_menu_basket : $this->basket;
        
        return str_limit(str_slug($basket->getName()), 3, '').''.
            $this->position.'-'.
            $this->recipe->name.'-'.
            (empty($this->basket_id) ? $this->weekly_menu_basket->portions : '');
        
    }
}