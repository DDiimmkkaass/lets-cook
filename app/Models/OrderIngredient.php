<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 15.07.16
 * Time: 18:20
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderIngredient
 * @package App\Models
 */
class OrderIngredient extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'basket_recipe_id',
        'ingredient_id',
        'name',
        'count',
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
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class)->with('sale_unit')->withTrashed();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
    {
        return $this->belongsTo(BasketRecipe::class, 'basket_recipe_id')->with('recipe');
    }
    
    /**
     * @param int|float $value
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
    public function scopeJoinBasketRecipe($query)
    {
        return $query->leftJoin('basket_recipes', 'basket_recipes.id', '=', 'order_ingredients.basket_recipe_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredient($query)
    {
        return $query->leftJoin('ingredients', 'ingredients.id', '=', 'order_ingredients.ingredient_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredientSupplier($query)
    {
        return $query->leftJoin('suppliers', 'suppliers.id', '=', 'ingredients.supplier_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredientCategory($query)
    {
        return $query->leftJoin('categories', 'categories.id', '=', 'ingredients.category_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredientUnit($query)
    {
        return $query->leftJoin('units', 'units.id', '=', 'ingredients.unit_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredientSaleUnit($query)
    {
        return $query->leftJoin('units', 'units.id', '=', 'ingredients.sale_unit_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinIngredientParameters($query)
    {
        return $query->leftJoin('ingredient_parameter', 'ingredient_parameter.ingredient_id', '=', 'ingredients.id')
            ->leftJoin('parameters', 'parameters.id', '=', 'ingredient_parameter.parameter_id');
    }
    
    /**
     * @return float|int
     */
    public function getPriceInOrder()
    {
        return $this->price * $this->count;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return empty($this->ingredient->name) ? $this->name : $this->ingredient->name;
    }
    
    /**
     * @return string
     */
    public function getSaleUnit()
    {
        return empty($this->ingredient->sale_unit) ? trans('front_labels.not_set'): $this->ingredient->sale_unit->name;
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        return empty($this->ingredient->image) ? '' : $this->ingredient->image;
    }
}