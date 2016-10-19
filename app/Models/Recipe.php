<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 14:02
 */

namespace App\Models;

use App\Contracts\FrontLink;
use App\Contracts\MetaGettable;
use App\Traits\Models\TaggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

/**
 * Class Recipe
 * @package App\Models
 */
class Recipe extends Model implements FrontLink, MetaGettable
{
    
    use SoftDeletes;
    use TaggableTrait;
    use SearchableTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'ingredients_image',
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
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'recipes.name'          => 100,
            'recipes.recipe'        => 50,
            'recipes.helpful_hints' => 25,
        ],
    ];
    
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
    public function order_recipes()
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
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinTags($query)
    {
        return $query->leftJoin('tagged', 'tagged.taggable_id', '=', 'recipes.id')
            ->whereRaw('taggable_type = \''.str_replace('\\', '\\\\', self::class).'\'')
            ->leftJoin('tags', 'tags.id', '=', 'tagged.tag_id');
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
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopeNameSorted($query, $order = 'ASC')
    {
        return $query->orderBy($this->getTable().'.name', $order);
    }
    
    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePortionsSorted($query, $order = 'ASC')
    {
        return $query->orderBy($this->getTable().'.portions', $order);
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
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return localize_route('recipes.show', $this->id);
    }
    
    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getMetaImage()
    {
        $img = empty($this->image) ? config('seo.share.default_image') : $this->image;
        
        return $img ? url($img) : $img;
    }
    
    /**
     * TODO: make normal rating
     *
     * @return int
     */
    public function getRating()
    {
        return rand(3, 5);
    }
}