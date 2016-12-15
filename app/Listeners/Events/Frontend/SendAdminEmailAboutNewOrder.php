<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 15.12.16
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\NewOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class SendAdminEmailAboutNewOrder
 * @package App\Listeners\Events\Frontend
 */
class SendAdminEmailAboutNewOrder implements ShouldQueue
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
        
        $email = variable('order_email');
        
        if ($email) {
            Mail::queue(
                'emails.admin.new_order',
                ['order' => serialize($order)],
                function ($message) use ($order, $email) {
                    $message->to($email, config('app.name'))
                        ->subject(trans('front_subjects.new order'));
                }
            );
        }
    }
}