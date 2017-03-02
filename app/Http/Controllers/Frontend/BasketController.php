<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.08.16
 * Time: 18:25
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Basket;
use App\Models\BasketSubscribe;
use App\Models\City;
use App\Models\Order;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;
use App\Services\OrderService;
use App\Services\WeeklyMenuService;
use Carbon;
use FlashMessages;
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
     * @var \App\Services\WeeklyMenuService
     */
    private $weeklyMenuService;
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * BasketController constructor.
     *
     * @param \App\Services\WeeklyMenuService $weeklyMenuService
     * @param \App\Services\OrderService      $orderService
     */
    public function __construct(WeeklyMenuService $weeklyMenuService, OrderService $orderService)
    {
        parent::__construct();
        
        $this->weeklyMenuService = $weeklyMenuService;
        $this->orderService = $orderService;
    }
    
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
            
            $new_year_basket = $this->weeklyMenuService->getNewYearBasket($menu->week);
        }
    
        $this->data('baskets', isset($baskets) ? $baskets->sortBy('position') : collect());
        $this->data('new_year_basket', isset($new_year_basket) ? $new_year_basket : null);
        $this->data('week', $week);
        
        Meta::canonical(localize_route('baskets.index', $week));
        
        $this->_fillIndexAdditionalTemplateData();
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @param string $slug
     * @param int    $portions
     * @param string $week
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug, $portions = 2, $week = 'current')
    {
        $active_week = active_week_menu_week();
        
        if ($week == 'next') {
            $active_week->addWeek();
        }
    
        $new_year_basket_slug = variable('new_year_basket_slug');
        
        if ($slug == $new_year_basket_slug) {
            $basket = WeeklyMenuBasket::with(['recipes', 'recipes.recipe.ingredients', 'recipes.recipe.home_ingredients'])
                ->joinWeeklyMenu()
                ->whereRaw('weekly_menu_id = (select id from weekly_menus where weekly_menus.year = '.(Carbon::now()->year + 1).' and weekly_menus.week = 1)')
                ->whereRaw('basket_id = (select id from baskets where baskets.slug = \''.$new_year_basket_slug.'\')')
                ->where('weekly_menu_baskets.portions', $portions)
                ->select('weekly_menu_baskets.*', 'weekly_menus.year', 'weekly_menus.week')
                ->first();
        } else {
            $basket = WeeklyMenuBasket::with(['recipes', 'recipes.recipe.ingredients', 'recipes.recipe.home_ingredients'])
                ->joinWeeklyMenu()
                ->joinBasket()
                ->where('baskets.slug', $slug)
                ->where('weekly_menus.year', $active_week->year)
                ->where('weekly_menus.week', $active_week->weekOfYear)
                ->where('weekly_menu_baskets.portions', $portions)
                ->select('weekly_menu_baskets.*', 'weekly_menus.year', 'weekly_menus.week')
                ->first();
        }
        
        if (!$basket) {
            $basket = Basket::whereSlug($slug)->first();
            
            abort_if(!$basket, 404);
    
            $this->data('basket', $basket);
            $this->data('class', 'not-available-basket-page');
            
            return $this->render($this->module.'.not_available');
        }
        
        $trial = request('trial', false);
        
        $this->data('trial', $trial);
        $this->data('basket', $basket);
        $this->data('same_basket', $this->weeklyMenuService->getSameBasket($basket), $slug == $new_year_basket_slug);
        $this->data('recipes_count', $trial ? 1 :
            ($slug == $new_year_basket_slug ? 7 : config('weekly_menu.default_recipes_count')));
        $this->data('selected_baskets', collect());
        $this->data('new_year_basket', $slug == $new_year_basket_slug);
        
        $this->_fillShowAdditionalTemplateData($basket);
        
        $this->fillMeta($basket, $this->module);
        
        return $this->render($this->module.'.show');
    }
    
    /**
     * @param string $slug
     * @param int $order_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function repeat($slug, $order_id)
    {
        if ($slug == variable('new_year_basket_slug')) {
            FlashMessages::add('error', trans('front_messages.you can not repeat this basket'));
        
            return redirect()->back();
        }
    
        $repeat_order = Order::with(
            'main_basket',
            'recipes',
            'additional_baskets',
            'main_basket.weekly_menu_basket.weekly_menu',
            'coupon'
        )
            ->notOfStatus(['deleted'])
            ->whereId($order_id)
            ->first();
        
        abort_if(!$repeat_order, 404);
        
        $basket = $this->orderService->getSameActiveBasket($repeat_order);
    
        if (!$basket) {
            $basket = Basket::whereSlug($slug)->first();
        
            abort_if(!$basket, 404);
        
            $this->data('basket', $basket);
            $this->data('class', 'not-available-basket-page');
        
            return $this->render($this->module.'.not_available');
        }
        
        $this->data('basket', $basket);
        $this->data('same_basket', null);
        $this->data('recipes_count', $repeat_order->recipes->count());
        $this->data('repeat_order', $repeat_order);
        $this->data('selected_baskets', $repeat_order->additional_baskets);
        $this->data('new_year_basket', false);
        
        $this->_fillShowAdditionalTemplateData($basket);
        
        $this->fillMeta($basket, $this->module);
        
        return $this->render($this->module.'.show');
    }
    
    /**
     * fill additional template data
     */
    private function _fillIndexAdditionalTemplateData()
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
                            'tag'     => $tag->tag,
                            'name'    => $tag->tag->name,
                            'baskets' => [],
                        ];
                    }
                    
                    $additional_baskets_tags[$tag->tag->id]['baskets'][] = $additional_basket;
                }
            }
        }
        $this->data('additional_baskets_tags', collect($additional_baskets_tags)->sortBy('name'));
    }
    
    /**
     * fill additional template data
     *
     * @param WeeklyMenuBasket $basket
     */
    private function _fillShowAdditionalTemplateData($basket)
    {
        $additional_baskets = Basket::with('recipes', 'tags', 'tags.tag.category')
            ->additional()
            ->positionSorted()
            ->get();
        $this->data('additional_baskets', $additional_baskets);
        
        $additional_baskets_tags = [];
        foreach ($additional_baskets as $additional_basket) {
            foreach ($additional_basket->tags as $tag) {
                if ($tag->tag->category && $tag->tag->category->status) {
                    if (!isset($additional_baskets_tags[$tag->tag->id])) {
                        $additional_baskets_tags[$tag->tag->id] = [
                            'tag'   => $tag->tag,
                            'name'  => $tag->tag->name,
                            'price' => $additional_basket->price,
                        ];
                    }
                    
                    $additional_baskets_tags[$tag->tag->id]['price'] = min(
                        $additional_baskets_tags[$tag->tag->id]['price'],
                        $additional_basket->price
                    );
                }
            }
        }
        $this->data('additional_baskets_tags', collect($additional_baskets_tags)->sortBy('name'));
    
        if ($basket->getSlug() == variable('new_year_basket_slug') && $basket->weekly_menu->week == 1) {
            $dt = Carbon::now()->endOfYear()->startOfDay();
            
            $delivery_dates = [
                clone $dt->subDays(2),
                $dt->addDay(),
            ];
        }
        if (!isset($delivery_dates)) {
            $delivery_dates = $this->weeklyMenuService->getDeliveryDates($basket->year, $basket->week);
        }
        $this->data('delivery_dates', $delivery_dates);
        
        $this->data('delivery_times', config('order.delivery_times'));
        
        $payment_methods = [];
        foreach (Order::getPaymentMethods() as $id => $payment_method) {
            $payment_methods[$id] = trans('front_labels.payment_method_'.$payment_method);
        }
        $this->data('payment_methods', $payment_methods);
        
        $subscribe_periods = [];
        foreach (BasketSubscribe::getSubscribePeriods() as $subscribe_period) {
            $subscribe_periods[$subscribe_period] = trans_choice(
                'front_labels.subscribe_period_label',
                $subscribe_period
            );
        }
        $this->data('subscribe_periods', $subscribe_periods);
        
        $this->data('cities', City::positionSorted()->nameSorted()->get());
    }
}