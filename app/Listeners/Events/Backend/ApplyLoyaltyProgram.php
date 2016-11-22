<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Listeners\Events\Backend;

use App\Events\Backend\CompletedOrderArchived;
use App\Models\Order;
use App\Models\UserCoupon;
use App\Services\CouponService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class ApplyLoyaltyProgram
 * @package App\Observers
 */
class ApplyLoyaltyProgram implements ShouldQueue
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
        if (variable('loyalty_program_status')) {
            $user = $event->order->user;
            
            if ($user) {
                $finished_orders_count = Order::ofUser($user->id)->finished()->count() + $user->oldsite_orders_count;
                
                foreach (array_reverse(config('coupons.loyalty_program'), true) as $level => $loyalty) {
                    if ($finished_orders_count >= $loyalty['orders']) {
                        $_coupon_key = 'loyalty_program_'.$level;
                        
                        $coupon = UserCoupon::ofUser($user->id)
                            ->joinCoupon()
                            ->where('coupons.key', $_coupon_key)
                            ->count();
                        
                        if (!$coupon) {
                            $coupon = $this->couponService->createLoyaltyCoupon(
                                $level,
                                $loyalty['orders'],
                                $loyalty['discount'],
                                $loyalty['count']
                            );
                            
                            $default = UserCoupon::ofUser($user->id)->default()->count();
                            
                            $this->couponService->saveUserCoupon($user, $coupon, !$default);
                        }
                        
                        break;
                    }
                }
            }
        }
    }
}