<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 06.09.16
 * Time: 15:25
 */

namespace App\Widgets\WeeklyMenu;

use App\Models\WeeklyMenu;
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
        $menu = WeeklyMenu::with('baskets', 'baskets.recipes')->current()->first();
        if ($menu && $menu->baskets->count()) {
            $menu = $menu->baskets->random();
        } else {
            $menu = false;
        }

        $next_menu = WeeklyMenu::with('baskets', 'baskets.recipes')->next()->first();
        if ($next_menu && $next_menu->baskets->count()) {
            $next_menu = $next_menu->baskets->random();
        } else {
            $next_menu = false;
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