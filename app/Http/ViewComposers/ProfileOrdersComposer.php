<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 12:51
 */

namespace App\Http\ViewComposers;

use App\Models\Basket;
use App\Models\BasketSubscribe;
use App\Models\Order;
use App\Models\User;
use App\Models\WeeklyMenu;
use App\Services\UserService;
use Carbon;
use Illuminate\View\View;
use Sentry;

/**
 * Class ProfileOrdersComposer
 * @package App\Http\ViewComposers
 */
class ProfileOrdersComposer
{
    /**
     * @var User
     */
    protected $user;
    
    /**
     * @var \App\Services\TagService
     */
    private $userService;
    
    /**
     * ProfileComposer constructor.
     *
     * @param \App\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        
        $this->user = Sentry::getUser();
    }
    
    /**
     * Bind data to the view.
     *
     * @param  View $view
     */
    public function compose(View $view)
    {
        $view->with('history_orders', $this->userService->getOrders(Sentry::getUser()->getId()));
        
        $weekly_menu = WeeklyMenu::with('baskets')->current()->first();
        $subscribe = BasketSubscribe::whereUserId($this->user->getId())->first();
        $additional_baskets = Basket::additional()->get(['id', 'name', 'price']);
        $tmpl_orders = Order::ofStatus('tmpl')
            ->with('main_basket', 'additional_baskets')
            ->whereUserId($this->user->getId())
            ->where('delivery_date', '>=', Carbon::now())
            ->orderBY('delivery_date', 'ASC')
            ->get();
    
        $subscribe_periods = [];
        foreach (BasketSubscribe::getSubscribePeriods() as $subscribe_period) {
            $subscribe_periods[$subscribe_period] = trans_choice(
                'front_labels.subscribe_period_label',
                $subscribe_period
            );
        }
        
        $view->with('weekly_menu', $weekly_menu);
        $view->with('subscribe', $subscribe);
        $view->with('tmpl_orders', $tmpl_orders);
        $view->with('additional_baskets', $additional_baskets);
        $view->with('subscribe_periods', $subscribe_periods);
    }
}