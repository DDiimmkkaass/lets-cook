<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.08.16
 * Time: 18:25
 */

namespace App\Http\Controllers\Frontend;

use App\Models\WeeklyMenu;

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
        $baskets = [];
        
        $menu = WeeklyMenu::with('baskets', 'baskets.recipes')->{$week}()->first();
        
        if ($menu) {
            $baskets = $menu->baskets;
        }
        
        $this->data('baskets', $baskets);
        
        return $this->render($this->module.'.index');
    }
}