<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 06.09.16
 * Time: 15:25
 */

namespace App\Widgets\WeeklyMenu;

use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;
use Illuminate\Support\Collection;
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
        
        $menus = WeeklyMenu::active()->get();
        
        $menu = false;
        $menu_baskets = [];
        
        $next_menu = false;
        $next_menu_baskets = [];
        
        foreach ($menus as $_menu) {
            if ($_menu->week == $active_week->weekOfYear) {
                $menu = $_menu;
    
                $menu_baskets = WeeklyMenuBasket::with('basket', 'recipes')
                    ->joinBasket()
                    ->where('weekly_menu_id', $menu->id)
                    ->groupBy('weekly_menu_baskets.basket_id')
                    ->orderBy('weekly_menu_baskets.portions', 'DESC')
                    ->get(['weekly_menu_baskets.*', 'baskets.position']);
            } else {
                $next_menu = $_menu;
    
                $next_menu_baskets = WeeklyMenuBasket::with('basket', 'recipes')
                    ->joinBasket()
                    ->where('weekly_menu_id', $next_menu->id)
                    ->groupBy('weekly_menu_baskets.basket_id')
                    ->orderBy('weekly_menu_baskets.portions', 'DESC')
                    ->get(['weekly_menu_baskets.*', 'baskets.position']);
            }
        }
        
        if (view()->exists('widgets.weekly_menu.templates.'.$template.'.index')) {
            $this->template = $template;
        }
        
        return view('widgets.weekly_menu.templates.'.$this->template.'.index')
            ->with('menu', $menu)->with('menu_baskets', $menu_baskets)
            ->with('next_menu', $next_menu)->with('next_menu_baskets', $next_menu_baskets)
            ->render();
    }
}