<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Listeners\Events\Backend;

use App\Events\Backend\CompletedOrderArchived;
use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;
use App\Services\CouponService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class ApplyInviteFriendsProgram
 * @package App\Observers
 */
class ApplyInviteFriendsProgram implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * @var \App\Services\CouponService
     */
    protected $couponService;
    
    /**
     * Create the event handler.
     *
     * @param \App\Services\CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    
    /**
     * Handle the event.
     *
     * @param CompletedOrderArchived $event
     */
    public function handle(CompletedOrderArchived $event)
    {
        if (
            (int) variable('invite_friend_compensation') > 0 &&
            variable('invite_friend_compensation_type') &&
            $event->order->coupon_id
        ) {
            $from_user = $event->order->user;
            $coupon = Coupon::whereId($event->order->coupon_id)
                ->whereNotNull('user_id')
                ->whereKey('invite_friend')
                ->first();
            
            if ($from_user && $coupon) {
                $for_user = User::find($coupon->user_id);
                $coupon = UserCoupon::joinCoupon()
                    ->ofUser($for_user->id)
                    ->where('coupons.key', 'invite_friend_compensation')
                    ->where('coupons.user_id', $from_user->id)
                    ->count();
                
                if ($for_user && !$coupon) {
                    $default = UserCoupon::ofUser($for_user->id)->default()->count();
                    
                    $this->couponService->giveInviteFriendCompensationCoupon($for_user, $from_user, !$default);
                }
            }
        }
    }
}