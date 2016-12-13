<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.07.16
 * Time: 16:33
 */

namespace App\Models;

use App\Contracts\FrontLink;
use App\Contracts\MetaGettable;
use Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WeeklyMenuBasket
 * @package App\Models
 */
class WeeklyMenuBasket extends Model implements FrontLink, MetaGettable
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'weekly_menu_id',
        'basket_id',
        'portions',
        'prices',
        'delivery_date',
    ];
    
    /**
     * @var array
     */
    protected $with = ['basket'];
    
    /**
     * @var array
     */
    protected $dates = ['delivery_date'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weekly_menu()
    {
        return $this->belongsTo(WeeklyMenu::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes()
    {
        return $this->hasMany(BasketRecipe::class)->with('recipe')->positionSorted();
    }
    
    /**
     * @param array $value
     */
    public function setPricesAttribute($value)
    {
        foreach ($value as $day => $price) {
            $value[$day] = (int) ($price * 100);
        }
        
        $this->attributes['prices'] = json_encode($value);
    }
    
    /**
     * @param string $value
     */
    public function setDeliveryDateAttribute($value)
    {
        $this->attributes['delivery_date'] = empty($value) ?
            null :
            Carbon::createFromFormat('d-m-Y', $value)->startOfDay()->format('Y-m-d H:i:s');
            
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
            
            foreach ($value as $day => $price) {
                $values[$day] = $price / 100;
            }
        }
        
        return $values;
    }
    
    /**
     * @param int $value
     *
     * @return int
     */
    public function getPortionsAttribute($value)
    {
        return (int) $value;
    }
    
    /**
     * @param string $value
     *
     * @return null|string
     */
    public function getDeliveryDateAttribute($value)
    {
        return empty($value) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $value)->startOfDay()->format('d-m-Y');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinWeeklyMenu($query)
    {
        return $query->rightJoin('weekly_menus', 'weekly_menus.id', '=', 'weekly_menu_baskets.weekly_menu_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBasket($query)
    {
        return $query->leftJoin('baskets', 'baskets.id', '=', 'weekly_menu_baskets.basket_id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBasketRecipes($query)
    {
        return $query->leftJoin(
            'basket_recipes',
            'basket_recipes.weekly_menu_basket_id',
            '=',
            'weekly_menu_baskets.id'
        );
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotEmpty($query)
    {
        return $query->whereExists(
            function ($query) {
                $query->select(DB::raw(1))
                    ->from('basket_recipes')
                    ->whereRaw('basket_recipes.weekly_menu_basket_id = weekly_menu_baskets.id');
            }
        );
    }
    
    /**
     * @param int $portions
     * @param int $days
     *
     * @return float
     */
    public function getPrice($portions = 0, $days = 0)
    {
        return $this->basket->getPrice(empty($portions) ? $this->portions : $portions, $days);
    }
    
    /**
     * @param int $days
     *
     * @return float
     */
    public function getPriceInOrder($days = 0)
    {
        $days = $days > 0 ? $days : $this->recipes->count();
    
        return isset($this->prices[$days]) ? $this->prices[$days] : 0;
    }
    
    /**
     * @param int $portions
     * @param int $days
     *
     * @return int
     */
    public function getPlaces($portions = 0, $days = 0)
    {
        return $this->basket->getPlaces(empty($portions) ? $this->portions : $portions, $days);
    }
    
    /**
     * @return float
     */
    public function getWeekPrice()
    {
        return $this->prices;
    }
    
    /**
     * @return float
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }
    
    /**
     * @return array
     */
    public function getFormatDeliveryDate()
    {
        $date = $this->getDeliveryDate();
        if ($date) {
            $date = Carbon::createFromFormat('d-m-Y', $date);
            
            $date = $date->format('d').' '.get_localized_date($date->format('Y-m-d'), 'Y-m-d', false, '', '%f');
        }
        
        return [$date];
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->basket->getName();
    }
    
    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->basket->getSlug();
    }
    
    /**
     * @param string $week
     *
     * @return string
     */
    public function getUrl($week = 'current')
    {
        return $this->basket->getUrl($week);
        
    }
    
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->basket->getCode();
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->basket->description;
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        return $this->basket->getImage();
    }
    
    /**
     * @return int
     */
    public function getInternalPrice()
    {
        if (empty($this->price)) {
            $this->price = 0;
            
            foreach ($this->recipes as $recipe) {
                $this->attributes['price'] += $recipe->recipe->getPrice();
            }
        }
        
        return $this->price;
    }
    
    /**
     * @return int
     */
    public function getRecipesCount()
    {
        return (int) $this->recipes->count();
    }
    
    /**
     * @return int
     */
    public function getPortions()
    {
        return (int) $this->portions;
    }
    
    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->basket->getMetaTitle();
    }
    
    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->basket->getMetaDescription();
    }
    
    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->basket->getMetaKeywords();
    }
    
    /**
     * @return string
     */
    public function getMetaImage()
    {
        return $this->basket->getMetaImage();
    }
}