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
     * @return bool
     */
    public function isCurrentWeekMenu()
    {
        return $this->year == Carbon::now()->year && $this->week == Carbon::now()->weekOfYear;
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCurrent($query)
    {
        return $query->where('year', '=', Carbon::now()->year)
            ->where('week', '=', Carbon::now()->weekOfYear);
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
    public function scopeJoinBasketRecipes($query)
    {
        return $query->leftJoin('basket_recipes', 'basket_recipes.weekly_menu_basket_id', '=', 'weekly_menu_baskets.id');
    }
}