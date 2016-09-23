<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 12:51
 */

namespace App\Http\ViewComposers;

use App\Services\UserService;
use Illuminate\View\View;
use Sentry;

/**
 * Class ProfileOrdersComposer
 * @package App\Http\ViewComposers
 */
class ProfileOrdersComposer
{
    /**
     * @var \App\Services\TagService
     */
    private $userService;
    
    /**
     * ProfileOrdersComposer constructor.
     *
     * @param \App\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Bind data to the view.
     *
     * @param  View $view
     */
    public function compose(View $view)
    {
        $view->with('history_orders', $this->userService->getOrders(Sentry::getUser()->getId()));
    }
}