<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 23.06.16
 * Time: 14:43
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use App\Traits\Models\TaggableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Basket
 * @package App\Models
 */
class Basket extends Model
{
    
    use PositionSortedTrait;
    use TaggableTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'prices',
        'places',
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
     * @param array $value
     */
    public function setPricesAttribute($value)
    {
        foreach ($value as $portion => $days) {
            foreach ($days as $day => $price) {
                $value[$portion][$day] = (int) ($price * 100);
            }
        }
        
        $this->attributes['prices'] = json_encode($value);
    }
    
    /**
     * @param int|array $value
     */
    public function setPlacesAttribute($value)
    {
        $this->attributes['places'] = json_encode($value);
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
     * @param array $value
     *
     * @return array
     */
    public function getPricesAttribute($value)
    {
        $values = [];

        if (!empty($value)) {
            $value = (array) json_decode($value);
            
            foreach ($value as $portion => $days) {
                foreach ((array) $days as $day => $price) {
                    $values[$portion][$day] = $price / 100;
                }
            }
        }
        
        return $values;
    }
    
    /**
     * @param string $value
     *
     * @return array|int
     */
    public function getPlacesAttribute($value)
    {
        $value = (array) json_decode($value);
        
        if ($this->isType('additional')) {
            return isset($value[0]) ? $value[0] : 0;
        }
    
        $values = [];
    
        foreach ($value as $portion => $days) {
            foreach ((array) $days as $day => $places) {
                $values[$portion][$day] = $places;
            }
        }
        
        return $values;
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
        return $query->where($this->getTable().'.type', self::getTypeIdByName('basic'));
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeAdditional($query)
    {
        return $query->where($this->getTable().'.type', self::getTypeIdByName('additional'));
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBasketOrder($query)
    {
        return $query->leftJoin('basket_order', 'basket_order.basket_id', '=', 'baskets.id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinOrders($query)
    {
        return $query->leftJoin('orders', 'orders.id', '=', 'basket_order.order_id');
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
    
    /**
     * @param int $portions
     * @param int $days
     *
     * @return array|float
     */
    public function getPrice($portions = 0, $days = 0)
    {
        if ($this->isType('additional')) {
            return $this->price;
        }
    
        if ($portions == 0 && $days == 0) {
            return $this->prices;
        }
        
        if ($portions > 0 && $days == 0) {
            return isset($this->prices[$portions]) ? $this->prices[$portions] : [];
        }
        
        return isset($this->prices[$portions][$days]) ? $this->prices[$portions][$days] : 0;
    }
    
    /**
     * @param int $portions
     * @param int $days
     *
     * @return array|int
     */
    public function getPlaces($portions = 0, $days = 0)
    {
        if ($this->isType('additional')) {
            return $this->places;
        }
    
        if ($portions == 0 && $days == 0) {
            return $this->places;
        }
    
        if ($portions > 0 && $days == 0) {
            return isset($this->places[$portions]) ? $this->places[$portions] : [];
        }
    
        return isset($this->places[$portions][$days]) ? $this->places[$portions][$days] : 0;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        if ($this->recipes->count()) {
            return $this->recipes->random()->getRecipeImage();
        }
        
        return '';
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