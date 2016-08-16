<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Backend;

use App\Events\Backend\TmplOrderSuccessfullyPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class SendUserEmailAboutSuccessfullyPaymentOnTmplOrder
 * @package App\Listeners\Events\Backend
 */
class SendUserEmailAboutSuccessfullyPaymentOnTmplOrder implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * Create the event handler.
     *
     * @return \App\Listeners\Events\Backend\SendUserEmailAboutSuccessfullyPaymentOnTmplOrder
     */
    public function __construct()
    {
    }
    
    /**
     * Handle the event.
     *
     * @param TmplOrderSuccessfullyPaid $event
     */
    public function handle(TmplOrderSuccessfullyPaid $event)
    {
        Mail::queue(
            'emails.user.tmpl_order_successfully_paid',
            [
                'order' => serialize($event->order),
            ],
            function ($message) use ($event) {
                $message->to($event->order->email, $event->order->getUserFullName())
                    ->subject(trans('subjects.tmpl_order_successfully_paid'));
            }
        );
    }
}