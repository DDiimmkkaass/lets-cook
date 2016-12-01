<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.08.16
 * Time: 18:25
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Basket;
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
    
        $this->_fillAdditionalTemplateData();
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * fill additional template data
     */
    private function _fillAdditionalTemplateData()
    {
        $additional_baskets = Basket::with('recipes', 'tags', 'tags.tag.category')
            ->additional()
            ->positionSorted()
            ->get();
        
        $additional_baskets_tags = [];
        foreach ($additional_baskets as $additional_basket) {
            foreach ($additional_basket->tags as $tag) {
                if ($tag->tag->category && $tag->tag->category->status) {
                    if (!isset($additional_baskets_tags[$tag->tag->id])) {
                        $additional_baskets_tags[$tag->tag->id] = [
                            'tag'   => $tag->tag,
                            'name'  => $tag->tag->name,
                            'baskets' => [],
                        ];
                    }
    
                    $additional_baskets_tags[$tag->tag->id]['baskets'][] = $additional_basket;
                }
            }
        }
        $this->data('additional_baskets_tags', collect($additional_baskets_tags)->sortBy('name'));
    }
}