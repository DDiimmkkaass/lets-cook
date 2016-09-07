<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.09.16
 * Time: 15:31
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Basket;
use App\Models\City;
use App\Models\WeeklyMenuBasket;
use Carbon\Carbon;

/**
 * Class OrderController
 * @package App\Http\Controllers\Frontend
 */
class OrderController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'order';
    
    /**
     * @param int $basket_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index($basket_id)
    {
        $basket = WeeklyMenuBasket::with(
            [
                'main_recipes',
                'main_recipes.recipe.ingredients',
                'main_recipes.recipe.home_ingredients',
            ]
        )->find($basket_id);
        
        abort_if(!$basket, 404);
        
        $this->data('basket', $basket);
        
        $this->_fillAdditionalTemplateData();
    
        return $this->render($this->module.'.index');
    }
    
    /**
     * fill additional template data
     */
    private function _fillAdditionalTemplateData()
    {
        $now = Carbon::now();
        
        $stop_day = variable('stop_ordering_date');
        $stop_time = variable('stop_ordering_time');
        
        $additional_baskets = Basket::with('recipes')->additional()->positionSorted()->get();
        $this->data('additional_baskets', $additional_baskets);
    
        $delivery_dates = [];
        
        if ($now->dayOfWeek > $stop_day || ($now->dayOfWeek == $stop_day && $now->format('H:i') >= $stop_time)) {
            $now->addWeek();
        }
        $delivery_dates[] = clone ($now->endOfWeek()->startOfDay());
        $delivery_dates[] = clone ($now->endOfWeek()->addDay()->endOfDay());
        $delivery_dates[] = clone ($now->endOfWeek()->startOfDay());
        $delivery_dates[] = clone ($now->endOfWeek()->addDay()->endOfDay());
        
        $this->data('delivery_dates', $delivery_dates);
    
        $this->data('delivery_times', config('order.delivery_times'));
    
        $this->data('cities', City::positionSorted()->nameSorted()->get());
    }
}