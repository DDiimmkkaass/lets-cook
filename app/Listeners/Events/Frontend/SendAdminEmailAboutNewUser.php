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
     * Handle the event.
     *
     * @param $event
     */
    public function handle($event)
    {
        $email = variable('user_activation_bcc_email');
        
        if ($email) {
            $user = $event->user;
            
            Mail::queue(
                'emails.admin.new_user',
                ['user' => serialize($user)],
                function ($message) use ($email) {
                    $message->to($email, config('app.name'))
                        ->subject(trans('front_subjects.new_user'));
                }
            );
        }
    }
}