<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.07.16
 * Time: 16:33
 */

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WeeklyMenu
 * @package App\Models
 */
class WeeklyMenu extends Model
{
    
    /**
     * @var array
     */
    protected $fillable = [
        'week',
        'year',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function baskets()
    {
        return $this->hasMany(WeeklyMenuBasket::class)->with('basket');
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
    public function getName()
    {
        return trans('labels.w_label').$this->week.', '.$this->year;
    }
    
    /**
     * @return array
     */
    public function getDeliveryDates()
    {
        $dt = Carbon::create($this->year, 1, 1, 0)->addWeek($this->week);
        
        $dates = [
            clone $dt->endOfWeek(),
            $dt->addDay(),
        ];
        
        foreach ($dates as $key => $date) {
            $dates[$key] = $date->format('d').' '.get_localized_date($date->format('Y-m-d'), 'Y-m-d', false, '', '%f');
        }
        
        return $dates;
    }
    
    /**
     * @return bool
     */
    public function isCurrentWeekMenu()
    {
        $dt = active_week_menu_week();
        
        return $this->year == $dt->year && $this->week == $dt->weekOfYear;
    }
    
    /**
     * @return bool
     */
    public function old()
    {
        $dt = active_week_menu_week();
    
        return $this->year <= $dt->year && $this->week < $dt->weekOfYear;
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCurrent($query)
    {
        $dt = active_week_menu_week();
    
        return $query->where('year', '=', $dt->year)->where('week', '=', $dt->weekOfYear);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNext($query)
    {
        $dt = active_week_menu_week()->addWeek();
        
        return $query->where('year', '=', $dt->year)->where('week', '=', $dt->weekOfYear);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where(
            function ($query) {
                $query->where(
                    function ($query) {
                        $query->current();
                    }
                )->orWhere(
                    function ($query) {
                        $query->next();
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
    public function scopeJoinWeeklyMenuBaskets($query)
    {
        return $query->leftJoin('weekly_menu_baskets', 'weekly_menu_baskets.weekly_menu_id', '=', 'weekly_menus.id');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinBaskets($query)
    {
        return $query->leftJoin(
            'baskets',
            'baskets.id',
            '=',
            'weekly_menu_baskets.basket_id'
        );
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
}