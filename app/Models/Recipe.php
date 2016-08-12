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
        'status',
        'draft',
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
        return $this->hasMany(RecipeIngredient::class)->with('ingredient')->normal()->positionSorted();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function home_ingredients()
    {
        return $this->hasMany(RecipeIngredient::class)->with('ingredient')->home()->positionSorted();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orders()
    {
        return $this->hasManyThrough(OrderRecipe::class, BasketRecipe::class, 'recipe_id', 'basket_recipe_id')
            ->dateSorted();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps()
    {
        return $this->hasMany(RecipeStep::class)->positionSorted();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable')->positionSorted();
    }
    
    /**
     * @return mixed
     */
    public function files()
    {
        return $this->media()->of('file');
    }
    
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBaskets($query)
    {
        return $query->leftJoin('basket_recipe', 'basket_recipe.recipe_id', '=', 'recipes.id')
            ->leftJoin('baskets', 'baskets.id', '=', 'basket_recipe.basket_id');
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
     * @param        $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->where($this->getTable().'.status', true)->where($this->getTable().'.draft', false);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinMainIngredient($query)
    {
        return $query->joinIngredients()
            ->joinIngredientIngredient()
            ->where('recipe_ingredients.main', true);
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
    
    /**
     * set and return price of recipe
     *
     * @return int|mixed
     */
    public function getPrice()
    {
        if (empty($this->price)) {
            $this->price = 0;
            
            foreach ($this->ingredients as $ingredient) {
                $this->attributes['price'] += $ingredient->ingredient->price * $ingredient->count;
            }
        }
        
        return $this->price;
    }
}