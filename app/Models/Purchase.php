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
        'type',
        'year',
        'week',
        'price',
        'count',
        'in_stock',
    ];
    
    /**
     * @var array
     */
    public static $types = [
        'recipe',
        'order',
    ];
    
    /**
     * fields that can be changed by ajax
     *
     * @var array
     */
    protected static $changeable_fields = ['price', 'in_stock', 'purchase_manager'];
    
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
     * @param     $query
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
    public function scopeForCurrentWeek($query)
    {
        $dt = active_week();
        
        return $query->forWeek($dt->year, $dt->weekOfYear);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeForFuture($query)
    {
        $dt = active_week();
        
        return $query->where('year', '>', $dt->year)
            ->orWhere(
                function ($query) use ($dt) {
                    $query->where('year', '=', $dt->year)
                        ->where('week', '>=', $dt->weekOfYear);
                }
            );
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
     * @return bool
     */
    public function isCurrentWeek()
    {
        $now = active_week();
        
        return $this->year == $now->year && $this->week == $now->weekOfYear;
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
    
    /**
     * @return string
     */
    public function getUnitName()
    {
        return $this->getStringType() == 'order' ? $this->ingredient->sale_unit->name : $this->ingredient->unit->name;
    }
    
    /**
     * @return array
     */
    public static function getChangeableFields()
    {
        return self::$changeable_fields;
    }
    
    /**
     * @param string $type
     *
     * @return int|string
     */
    public static function getTypeIdByName($type)
    {
        foreach (self::$types as $id => $_type) {
            if ($_type == $type) {
                return $id;
            }
        }
        
        return null;
    }
    
    /**
     * @return string
     */
    public function getStringType()
    {
        foreach (self::$types as $id => $type) {
            if ($id == $this->type) {
                return $type;
            }
        }
        
        return '';
    }
    
    /**
     * @param string $type
     *
     * @return bool
     */
    public function isType($type)
    {
        return $this->type == self::getTypeIdByName($type);
    }
}