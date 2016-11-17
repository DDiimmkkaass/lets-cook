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
 * Class GiveRegistrationCoupon
 * @package App\Listeners\Events\Frontend
 */
class GiveRegistrationCoupon implements ShouldQueue
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
        if (variable('registration_coupon_discount')) {
            $coupon = $this->couponService->giveRegistrationCoupon($event->user);
            
            Mail::queue(
                'emails.auth.register_coupon',
                ['coupon' => serialize($coupon)],
                function ($message) use ($event) {
                    $message->to($event->user->email, $event->user->getFullName())
                        ->subject(trans('front_subjects.your discount coupon'));
                }
            );
        }
    }
}