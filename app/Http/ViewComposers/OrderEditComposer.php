<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 12:51
 */

namespace App\Http\ViewComposers;

use App\Models\User;
use Illuminate\View\View;
use Sentry;

/**
 * Class OrderEditComposer
 * @package App\Http\ViewComposers
 */
class OrderEditComposer
{
    /**
     * @var User
     */
    protected $user;
    
    /**
     * ProfileComposer constructor.
     */
    public function __construct()
    {
        $this->user = Sentry::getUser();
    }
    
    /**
     * Bind data to the view.
     *
     * @param  View $view
     */
    public function compose(View $view)
    {
        $view->with('user_coupons', $this->user->coupons()->get());
    }
}