<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Event;
use App\Services\CouponService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class CreateInviteFriendCoupon
 * @package App\Listeners\Events\Frontend
 */
class CreateInviteFriendCoupon implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * @var \App\Services\CouponService
     */
    private $couponService;
    
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
     * @param \App\Events\Event $event
     */
    public function handle(Event $event)
    {
        if ((int) variable('invite_friend_discount') > 0) {
            $this->couponService->createInviteFriendCoupon($event->user);
        }
    }
}