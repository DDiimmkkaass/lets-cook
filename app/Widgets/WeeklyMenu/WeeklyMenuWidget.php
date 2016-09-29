<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 06.09.16
 * Time: 15:25
 */

namespace App\Widgets\WeeklyMenu;

use App\Models\WeeklyMenu;
use DB;
use Pingpong\Widget\Widget;

/**
 * Class WeeklyMenuWidget
 * @package App\Widgets\WeeklyMenu
 */
class WeeklyMenuWidget extends Widget
{
    
    /**
     * @var string
     */
    protected $template = 'default';
    
    /**
     * @param string $template
     *
     * @return mixed
     */
    public function index($template = '')
    {
        $active_week = active_week_menu_week();
        
        $menus = WeeklyMenu::joinWeeklyMenuBaskets()->joinBasketRecipes()
            ->with('baskets', 'baskets.recipes')
            ->active()
            ->whereExists(
                function ($query) {
                    $query->select(DB::raw(1))
                        ->from('basket_recipes')
                        ->whereRaw('basket_recipes.weekly_menu_basket_id = weekly_menu_baskets.id')
                        ->whereRaw('weekly_menu_baskets.weekly_menu_id = weekly_menus.id');
                }
            )
            ->select('weekly_menus.*')
            ->groupBy('weekly_menus.id')
            ->take(2)
            ->get();
        
        $menu = false;
        $next_menu = false;
        
        foreach ($menus as $_menu) {
            if ($_menu->week == $active_week->weekOfYear) {
                if ($_menu->baskets->count()) {
                    $menu = $_menu->baskets->random();
                } else {
                    $menu = false;
                }
            } else {
                if ($_menu->baskets->count()) {
                    $next_menu = $_menu->baskets->random();
                } else {
                    $next_menu = false;
                }
            }
        }
        
        if (view()->exists('widgets.weekly_menu.templates.'.$template.'.index')) {
            $this->template = $template;
        }
        
        return view('widgets.weekly_menu.templates.'.$this->template.'.index')
            ->with('menu', $menu)
            ->with('next_menu', $next_menu)
            ->render();
    }
}