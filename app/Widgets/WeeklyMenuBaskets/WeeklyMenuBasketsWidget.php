<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 06.09.16
 * Time: 15:25
 */

namespace App\Widgets\WeeklyMenuBaskets;

use App\Models\WeeklyMenu;
use Pingpong\Widget\Widget;

/**
 * Class WeeklyMenuBasketsWidget
 * @package App\Widgets\WeeklyMenuBaskets
 */
class WeeklyMenuBasketsWidget extends Widget
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
        $menu = WeeklyMenu::with('baskets', 'baskets.main_recipes')->current()->first();
        
        if (view()->exists('widgets.weekly_menu_baskets.templates.'.$template.'.index')) {
            $this->template = $template;
        }
    
        return view('widgets.weekly_menu_baskets.templates.'.$this->template.'.index')->with('menu', $menu)->render();
    }
}