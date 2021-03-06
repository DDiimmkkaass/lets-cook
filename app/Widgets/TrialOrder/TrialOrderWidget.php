<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 06.09.16
 * Time: 15:25
 */

namespace App\Widgets\TrialOrder;

use App\Models\WeeklyMenuBasket;
use Pingpong\Widget\Widget;

/**
 * Class TrialOrderWidget
 * @package App\Widgets\TrialOrder
 */
class TrialOrderWidget extends Widget
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
        $basket_id = variable('trial_order_basket_id', false);
        
        if ($basket_id) {
            $active_week = active_week_menu_week();
            
            $basket = WeeklyMenuBasket::whereBasketId($basket_id)
                ->wherePortions(2)
                ->whereRaw('weekly_menu_id = 
                    (select weekly_menus.id 
                    from weekly_menus
                    where weekly_menus.year = '.$active_week->year.' and weekly_menus.week = '.$active_week->weekOfYear.'
                    )'
                )
                ->first();
            
            if ($basket) {
                if (view()->exists('widgets.trial_order.templates.'.$template.'.index')) {
                    $this->template = $template;
                }
                
                return view('widgets.trial_order.templates.'.$this->template.'.index')
                    ->with('basket', $basket)
                    ->render();
            }
        }
        
        return null;
    }
}