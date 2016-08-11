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
     * @return int
     */
    public function getPrice()
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