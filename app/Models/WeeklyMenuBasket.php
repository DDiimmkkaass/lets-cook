<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.07.16
 * Time: 16:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WeeklyMenuBasket
 * @package App\Models
 */
class WeeklyMenuBasket extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'weekly_menu_id',
        'basket_id',
        'portions',
        'prices',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weekly_menu()
    {
        return $this->belongsTo(WeeklyMenu::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes()
    {
        return $this->hasMany(BasketRecipe::class)->with('recipe');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function main_recipes()
    {
        return $this->hasMany(BasketRecipe::class)->whereMain(true)->with('recipe');
    }
    
    /**
     * @param array $value
     */
    public function setPricesAttribute($value)
    {
        foreach ($value as $day => $price) {
            $value[$day] = (int) ($price * 100);
        }
        
        $this->attributes['prices'] = json_encode($value);
    }
    
    /**
     * @param array $value
     *
     * @return array
     */
    public function getPricesAttribute($value)
    {
        $values = [];
        
        if (!empty($value)) {
            $value = (array) json_decode($value);
            
            foreach ($value as $day => $price) {
                $values[$day] = $price / 100;
            }
        }
        
        return $values;
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinWeeklyMenu($query)
    {
        return $query->rightJoin('weekly_menus', 'weekly_menus.id', '=', 'weekly_menu_baskets.weekly_menu_id');
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
    public function scopeJoinBasketRecipes($query)
    {
        return $query->leftJoin(
            'basket_recipes',
            'basket_recipes.weekly_menu_basket_id',
            '=',
            'weekly_menu_baskets.id'
        );
    }
    
    /**
     * @param int $portions
     * @param int $days
     *
     * @return float
     */
    public function getPrice($portions = 0, $days = 0)
    {
        return $this->basket->getPrice(empty($portions) ? $this->portions : $portions, $days);
    }
    
    /**
     * @param int $portions
     * @param int $days
     *
     * @return int
     */
    public function getPlaces($portions = 0, $days = 0)
    {
        return $this->basket->getPlaces(empty($portions) ? $this->portions : $portions, $days);
    }
    
    /**
     * @return float
     */
    public function getWeekPrice()
    {
        return $this->prices;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->basket->name;
    }
    
    /**
     * @return int
     */
    public function getInternalPrice()
    {
        if (empty($this->price)) {
            $this->price = 0;
            
            foreach ($this->recipes as $recipe) {
                $this->attributes['price'] += $recipe->recipe->getPrice();
            }
        }
        
        return $this->price;
    }
}