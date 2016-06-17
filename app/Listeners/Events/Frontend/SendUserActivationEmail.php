<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\UserRegister;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class SendUserActivationEmail
 * @package App\Listeners\Events\Frontend
 */
class SendUserActivationEmail implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Create the event handler.
     *
     * @return \App\Listeners\Events\FrontEnd\SendUserActivationEmail
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\FrontEnd\UserRegister $event
     */
    public function handle(UserRegister $event)
    {
        Mail::queue(
            'emails.auth.activation',
            ['user' => serialize($event->user), 'password' => $event->input['password']],
            function ($message) use ($event) {
                $message->to($event->user->email, $event->user->getFullName())
                    ->subject(trans('subjects.profile activation').' '.config('app.name'));
            }
        );
    }
}