<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\NewOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class SendUserEmailWithOrderInfo
 * @package App\Listeners\Events\Frontend
 */
class SendUserEmailWithOrderInfo implements ShouldQueue
{

    use InteractsWithQueue;
    
    /**
     * Handle the event.
     *
     * @param \App\Events\Frontend\NewOrder $event
     */
    public function handle(NewOrder $event)
    {
        $order = $event->order;
        
        Mail::queue(
            'emails.user.new_order',
            ['order' => serialize($order)],
            function ($message) use ($order) {
                $message->to($order->email, $order->getUserFullName())
                    ->subject(trans('front_subjects.your new order'));
            }
        );
    }
}