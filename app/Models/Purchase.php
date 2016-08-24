<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.08.16
 * Time: 14:33
 */

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchase
 * @package App\Models
 */
class Purchase extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'ingredient_id',
        'supplier_id',
        'year',
        'week',
        'count',
        'in_stock',
        'buy_count',
        'purchase_manager',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class)->with('unit')->withTrashed();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    /**
     * @param $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }
    
    /**
     * @param $value
     *
     * @return float
     */
    public function getPriceAttribute($value)
    {
        return $value / 100;
    }
    
    /**
     * @param $query
     * @param int $year
     * @param int $week
     *
     * @return mixed
     */
    public function scopeForWeek($query, $year, $week)
    {
        return $query->where('year', $year)->where('week', $week);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeForNextWeek($query)
    {
        return $query->forWeek(Carbon::now()->year, Carbon::now()->weekOfYear);
    }
    
    /**
     * @param $query
     */
    public function scopeJoinIngredient($query)
    {
        return $query->leftJoin('ingredients', 'ingredients.id', '=', 'purchases.ingredient_id');
    }
    
    /**
     * @param $query
     */
    public function scopeJoinIngredientUnit($query)
    {
        return $query->leftJoin('units', 'units.id', '=', 'ingredients.unit_id');
    }
    
    /**
     * @param $query
     */
    public function scopeJoinIngredientRecipe($query)
    {
        return $query->leftJoin('units', 'units.id', '=', 'ingredients.unit_id');
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
     * @return string
     */
    public function getWeekDates()
    {
        $dt = Carbon::create($this->year, 1, 1, 0)->addWeek($this->week);
        
        $started_at = $dt->startOfWeek()->format('Y-m-d');
        $ended_at = $dt->endOfWeek()->format('Y-m-d');
        
        return $started_at.' - '.$ended_at;
    }
}