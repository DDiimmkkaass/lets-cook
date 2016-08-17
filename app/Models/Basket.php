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
 * Class Basket
 * @package App\Models
 */
class Basket extends Model
{
    
    use PositionSortedTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'position',
    ];
    
    /**
     * @var array
     */
    public static $types = [
        1 => 'basic',
        2 => 'additional',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allowed_recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipes()
    {
        return $this->hasMany(BasketRecipe::class)->positionSorted()->with('recipe');
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
     * @param        $query
     * @param string $type
     *
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        $id = self::getTypeIdByName($type);
        
        return $query->whereType($id);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeBasic($query)
    {
        return $query->whereType(self::getTypeIdByName('basic'));
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeAdditional($query)
    {
        return $query->whereType(self::getTypeIdByName('additional'));
    }
    
    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * @param string $type
     *
     * @return int|string
     */
    public static function getTypeIdByName($type)
    {
        foreach (self::$types as $key => $_type) {
            if ($_type == $type) {
                return $key;
            }
        }
    }
}