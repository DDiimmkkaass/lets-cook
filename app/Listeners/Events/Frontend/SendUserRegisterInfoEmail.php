<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\UserQuickRegister;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class SendUserRegisterInfoEmail
 * @package App\Listeners\Events\Frontend
 */
class SendUserRegisterInfoEmail implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param \App\Events\FrontEnd\UserQuickRegister $event
     */
    public function handle(UserQuickRegister $event)
    {
        Mail::queue(
            'emails.auth.register_info',
            ['user' => serialize($event->user), 'password' => $event->input['password']],
            function ($message) use ($event) {
                $message->to($event->user->email, $event->user->getFullName())
                    ->subject(trans('subjects.congrats with register').' '.config('app.name'));
            }
        );
    }
}