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
        return $this->belongsTo(Recipe::class);
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
}