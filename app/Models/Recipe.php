<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 14:02
 */

namespace App\Models;

use App\Traits\Models\VisibleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Recipe
 * @package App\Models
 */
class Recipe extends Model
{
    
    use SoftDeletes;
    use VisibleTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'recipe',
        'helpful_hints',
        'portions',
        'cooking_time',
        'home_equipment',
        'home_ingredients',
        'status',
        'price',
    ];
    
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function baskets()
    {
        return $this->belongsToMany(Basket::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class)->with('ingredient')->positionSorted();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps()
    {
        return $this->hasMany(RecipeStep::class)->positionSorted();
    }
    
    /**
     * @param float|int $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) ($value * 100);
    }
    
    /**
     * @param int $value
     *
     * @return float
     */
    public function getPriceAttribute($value)
    {
        return $value / 100;
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredients($query)
    {
        return $query->leftJoin('recipe_ingredients', 'recipe_ingredients.recipe_id', '=', 'recipes.id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredientIngredient($query)
    {
        return $query->leftJoin('ingredients', 'ingredients.id', '=', 'recipe_ingredients.ingredient_id');
    }
    
    /**
     * set as value & return main ingredient for recipe
     *
     * @return RecipeIngredient|null
     */
    public function mainIngredient()
    {
        if (empty($this->main_ingredient)) {
            $this->main_ingredient = null;
            
            foreach ($this->ingredients as $ingredient) {
                if ($ingredient->main) {
                    $this->attributes['main_ingredient'] = $ingredient->ingredient;
                    
                    continue;
                }
            }
        }
        
        return $this->main_ingredient;
    }
}