<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 12:40
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IngredientNutritionalValue
 * @package App\Models
 */
class IngredientNutritionalValue extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'ingredient_id',
        'nutritional_value_id',
        'value',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nutritional_value()
    {
        return $this->belongsTo(NutritionalValue::class);
    }
    
    /**
     * @param int|float $value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = (int) ($value * 100);
    }
    
    /**
     * @param int $value
     *
     * @return float
     */
    public function getValueAttribute($value)
    {
        return $value / 100;
    }
}