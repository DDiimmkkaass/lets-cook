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
        'started_at',
        'ended_at',
    ];
    
    /**
     * @var array
     */
    protected $dates = ['started_at', 'ended_at'];
    
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
    public function getStartedAt()
    {
        if (empty($this->started_at) || $this->started_at == '0000-00-00 00:00:00') {
            return null;
        } else {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->started_at)->format('d.m.Y');
        }
    }
    
    /**
     * @return string
     */
    public function getEndedAt()
    {
        if (empty($this->ended_at) || $this->ended_at == '0000-00-00 00:00:00') {
            return null;
        } else {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->ended_at)->format('d.m.Y');
        }
    }
    
    /**
     * @return string
     */
    public function getWeekDates()
    {
        $started_at = $this->getStartedAt();
        $ended_at = $this->getEndedAt();
        
        return $started_at && $ended_at ? $started_at.' - '.$ended_at : '';
    }
    
    /**
     * @return bool
     */
    public function isCurrentWeekMenu()
    {
        return $this->started_at <= Carbon::now() && $this->ended_at >= Carbon::now();
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCurrent($query)
    {
        return $query->where('started_at', '<=', Carbon::now())->where('ended_at', '>=', Carbon::now());
    }
    
    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopeStartedAtSorted($query, $order = 'DESC')
    {
        return $query->orderBy('started_at', $order);
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
        return $query->leftJoin('basket_recipes', 'basket_recipes.weekly_menu_basket_id', '=', 'weekly_menu_baskets.basket_id');
    }
}