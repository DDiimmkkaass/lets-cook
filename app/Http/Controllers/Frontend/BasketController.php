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
    public function index($week = 'current')
    {
        if ($week == 'current') {
            $menu = WeeklyMenu::current()->first();
        } else {
            $menu = WeeklyMenu::next()->first();
        }
        
        $baskets = WeeklyMenuBasket::with('basket', 'recipes')
            ->joinBasket()
            ->where('weekly_menu_id', $menu->id)
            ->groupBy('weekly_menu_baskets.basket_id')
            ->orderBy('weekly_menu_baskets.portions', 'DESC')
            ->get(['weekly_menu_baskets.*', 'baskets.position']);
        
        $this->data('baskets', $baskets->sortBy('position'));
        
        return $this->render($this->module.'.index');
    }
}