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
 * Class SendAdminEmailAboutNewUser
 * @package App\Listeners\Events\Frontend
 */
class SendAdminEmailAboutNewUser implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Create the event handler.
     *
     * @return \App\Listeners\Events\Frontend\SendAdminEmailAboutNewUser
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param UserRegister $event
     */
    public function handle(UserRegister $event)
    {
        Mail::queue(
            'emails.admin.new_user',
            ['user' => serialize($event->user)],
            function ($message) {
                $message->to(config('app.email'), config('app.name'))->subject(trans('subjects.new_user'));
            }
        );
    }
}