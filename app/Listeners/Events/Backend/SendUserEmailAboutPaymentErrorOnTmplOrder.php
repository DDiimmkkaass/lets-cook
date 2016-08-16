<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Backend;

use App\Events\Backend\TmplOrderPaymentError;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class SendUserEmailAboutPaymentErrorOnTmplOrder
 * @package App\Listeners\Events\Backend
 */
class SendUserEmailAboutPaymentErrorOnTmplOrder implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * Create the event handler.
     *
     * @return \App\Listeners\Events\Backend\SendUserEmailAboutPaymentErrorOnTmplOrder
     */
    public function __construct()
    {
    }
    
    /**
     * Handle the event.
     *
     * @param TmplOrderPaymentError $event
     */
    public function handle(TmplOrderPaymentError $event)
    {
        Mail::queue(
            'emails.user.tmpl_order_payment_error',
            [
                'order' => serialize($event->order),
                '_message' => $event->message
            ],
            function ($message) use ($event) {
                $message->to($event->order->email, $event->order->getUserFullName())
                    ->subject(trans('subjects.tmpl_order_payment_error'));
            }
        );
    }
}