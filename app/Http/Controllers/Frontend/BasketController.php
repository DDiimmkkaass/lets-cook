<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.08.16
 * Time: 18:25
 */

namespace App\Http\Controllers\Frontend;

use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;
use Meta;

/**
 * Class BasketController
 * @package App\Http\Controllers\Frontend
 */
class BasketController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'basket';
    
    /**
     * @param string $week
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index($week)
    {
        if ($week == 'current') {
            $menu = WeeklyMenu::current()->first();
        } else {
            $menu = WeeklyMenu::next()->first();
        }
        
        if ($menu) {
            $baskets = WeeklyMenuBasket::with('basket', 'recipes')
                ->joinBasket()
                ->where('weekly_menu_id', $menu->id)
                ->groupBy('weekly_menu_baskets.basket_id')
                ->orderBy('weekly_menu_baskets.portions', 'DESC')
                ->get(['weekly_menu_baskets.*', 'baskets.position']);
        }
        
        $this->data('baskets', isset($baskets) ? $baskets->sortBy('position') : collect());
    
        Meta::canonical(localize_route('baskets.index', $week));
        
        return $this->render($this->module.'.index');
    }
}