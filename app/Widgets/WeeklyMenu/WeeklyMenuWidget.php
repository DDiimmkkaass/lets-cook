<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 06.09.16
 * Time: 15:25
 */

namespace App\Widgets\WeeklyMenu;

use App\Models\WeeklyMenu;
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
        $next_menu = false;
        
        foreach ($menus as $_menu) {
            if ($_menu->week == $active_week->weekOfYear) {
                $baskets = $this->_getBaskets($_menu);
                
                if ($baskets->count()) {
                    $menu = $baskets->random();
                }
            } else {
                $baskets = $this->_getBaskets($_menu);
                
                if ($baskets->count()) {
                    $next_menu = $baskets->random();
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
    
    /**
     * @param \App\Models\WeeklyMenu $_menu
     *
     * @return Collection
     */
    private function _getBaskets(WeeklyMenu $_menu)
    {
        return $_menu->baskets()->joinBasketRecipes()->with('recipes')->notEmpty()->get(['weekly_menu_baskets.*']);
    }
}