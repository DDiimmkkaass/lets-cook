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
        'weekly_menu_id',
        'basket_id',
        'recipe_id',
        'main',
        'portions',
        'position',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }
    
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
    public function setWeeklyMenuIdAttribute($value)
    {
        $this->attributes['weekly_menu_id'] = empty($value) ? null : (int) $value;
    }
}