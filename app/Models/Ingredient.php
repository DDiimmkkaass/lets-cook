<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 14:02
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ingredient
 * @package App\Models
 */
class Ingredient extends Model
{
    
    use SoftDeletes;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'image',
        'price',
        'sale_price',
        'repacking',
        'supplier_id',
        'category_id',
        'unit_id',
        'sale_unit_id',
    ];
    
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale_unit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parameters()
    {
        return $this->belongsToMany(Parameter::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nutritional_values()
    {
        return $this->hasMany(IngredientNutritionalValue::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipe()
    {
        return $this->belongsToMany(RecipeIngredient::class);
    }
    
    /**
     * @param $value
     */
    public function setSupplierIdAttribute($value)
    {
        $this->attributes['supplier_id'] = empty($value) ? null : (int) $value;
    }
    
    /**
     * @param $value
     */
    public function setCategoryIdAttribute($value)
    {
        $this->attributes['category_id'] = empty($value) ? null : (int) $value;
    }
    
    /**
     * @param $value
     */
    public function setUnitIdAttribute($value)
    {
        $this->attributes['unit_id'] = empty($value) ? null : (int) $value;
    }
    
    /**
     * @param $value
     */
    public function setSaleUnitIdAttribute($value)
    {
        $this->attributes['sale_unit_id'] = empty($value) ? null : (int) $value;
    }
    
    /**
     * @param int|float $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) ($value * 100);
    }
    
    /**
     * @param int|float $value
     */
    public function setSalePriceAttribute($value)
    {
        $this->attributes['sale_price'] = (int) ($value * 100);
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
     * @param int $value
     *
     * @return float
     */
    public function getSalePriceAttribute($value)
    {
        return $value / 100;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return empty($this->title) ? $this->name : $this->title;
    }
    
    /**
     * @return bool
     */
    public function inSale()
    {
        return $this->sale_price > 0;
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinSupplier($query)
    {
        return $query->leftJoin('suppliers', 'suppliers.id', '=', 'ingredients.supplier_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinCategory($query)
    {
        return $query->leftJoin('categories', 'categories.id', '=', 'ingredients.category_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinUnit($query)
    {
        return $query->leftJoin('units', 'units.id', '=', 'ingredients.unit_id');
    }
    
    /**
     * @param        $query
     * @param string $table_name
     *
     * @return mixed
     */
    public function scopeJoinSaleUnit($query, $table_name = 'units')
    {
        return $query->leftJoin(DB::raw('units as '.$table_name), $table_name.'.id', '=', 'ingredients.sale_unit_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinParameters($query)
    {
        return $query->leftJoin('ingredient_parameter', 'ingredient_parameter.ingredient_id', '=', 'ingredients.id')
            ->leftJoin('parameters', 'parameters.id', '=', 'ingredient_parameter.parameter_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinRecipeIngredients($query)
    {
        return $query->leftJoin('recipe_ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('category_id')->whereNotNull('supplier_id')->whereNotNull('unit_id')
            ->where(
                function ($query) {
                    $query->where('sale_price', '=', 0)
                        ->orWhere(
                            function ($query) {
                                $query->where('sale_price', '>', 0)
                                    ->whereNotNull('sale_unit_id');
                            }
                        );
                }
            );
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeInSales($query)
    {
        return $query->where('sale_price', '>', 0);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotSales($query)
    {
        return $query->where('sale_price', '=', 0);
    }
    
    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopeNameSorted($query, $order = 'ASC')
    {
        return $query->orderBy('name', $order);
    }
}