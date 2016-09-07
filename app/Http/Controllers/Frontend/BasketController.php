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
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $baskets = [];
        
        $menu = WeeklyMenu::with('baskets', 'baskets.main_recipes')->current()->first();
        
        if ($menu) {
            $baskets = $menu->baskets;
        }
        
        $this->data('baskets', $baskets);
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @param int $basket_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($basket_id)
    {
        $model = WeeklyMenuBasket::with('main_recipes')->find($basket_id);
        
        abort_if(!$model, 404);
        
        $this->fillMeta($model, $this->module);
        
        $this->data('model', $model);
        
        return $this->render($this->module.'.show');
    }
}