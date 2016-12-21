<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 12:51
 */

namespace App\Http\ViewComposers;

use App\Models\Coupon;
use App\Services\CouponService;
use Carbon;
use Illuminate\View\View;
use Sentry;

/**
 * Class InviteFriendCouponComposer
 * @package App\Http\ViewComposers
 */
class InviteFriendCouponComposer
{
    /**
     * @var \App\Services\CouponService
     */
    private $couponService;
    
    /**
     * UserCouponsComposer constructor.
     *
     * @param \App\Services\CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    
    /**
     * Bind data to the view.
     *
     * @param  View $view
     */
    public function compose(View $view)
    {
        $user = Sentry::getUser();
        $invite_friend = false;
        
        if ($user) {
            $invite_friend = Coupon::whereUserId($user->id)
                ->whereKey('invite_friend')
                ->whereRaw('(expired_at >= ? OR expired_at IS NULL)', [Carbon::now()])
                ->first();
            
            if (
                !$invite_friend &&
                (int) variable('invite_friend_discount') > 0 &&
                variable('invite_friend_discount_type')
            ) {
                $invite_friend = $this->couponService->createInviteFriendCoupon($user);
            }
        }
        
        $view->with('invite_friend', $invite_friend);
    }
}