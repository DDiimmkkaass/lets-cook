<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 12:51
 */

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Sentry;

/**
 * Class UserCouponsComposer
 * @package App\Http\ViewComposers
 */
class UserCouponsComposer
{
    
    /**
     * Bind data to the view.
     *
     * @param  View $view
     */
    public function compose(View $view)
    {
        $user = Sentry::getUser();
        
        if ($user) {
            $user_id = $user->id;
            $default = false;
            
            $user_coupons = $user->coupons()->with(
                [
                    'orders' => function ($query) use ($user_id) {
                        $query->whereUserId($user_id);
                    },
                ]
            )->get()->keyBy('id');
            
            $_user_coupons = $user_coupons->filter(
                function ($item) use ($user, &$default) {
                    if ($item->available($user)) {
                        $default = $item->default ? true : $default;
                        
                        return true;
                    }
                    
                    return false;
                }
            );
            
            if (!$default) {
                $coupon = $_user_coupons->last();
                
                $coupon->default = true;
                $coupon->save();
                
                $user_coupons->put($coupon->id, $coupon);
            }
        } else {
            $user_coupons = [];
        }
        
        $view->with('user_coupons', $user_coupons);
    }
}