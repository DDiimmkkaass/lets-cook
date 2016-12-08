<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\NewComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

/**
 * Class SendAdminEmailAboutNewComment
 * @package App\Listeners\Events\Frontend
 */
class SendAdminEmailAboutNewComment implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     *
     * @param NewComment $event
     */
    public function handle(NewComment $event)
    {
        Mail::queue(
            'emails.admin.new_comment',
            [
                'comment' => serialize($event->comment),
                'user'    => serialize($event->user),
            ],
            function ($message) use ($event) {
                $message->to(
                    config('app.email'),
                    config('app.name')
                )->subject(trans('front_subjects.new_comment'));
            }
        );
    }
}