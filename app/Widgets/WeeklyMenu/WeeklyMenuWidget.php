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
        
        $menus = WeeklyMenu::with('baskets', 'baskets.recipes')->active()->get();
        
        $menu = false;
        $next_menu = false;
        
        foreach ($menus as $_menu) {
            if ($_menu->week == $active_week->weekOfYear) {
                $menu = $_menu;
            } else {
                $next_menu = $_menu;
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