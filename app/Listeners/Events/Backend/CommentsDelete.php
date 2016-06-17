<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Backend;

use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CommentsDelete
 * @package App\Listeners\Events\Backend
 */
class CommentsDelete implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Create the event handler.
     *
     * @return \App\Listeners\Events\Backend\CommentsDelete
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param $event
     */
    public function handle($event)
    {
        Comment::whereCommentableType($event->commentable_type)->whereCommentableId($event->commentable_id)->delete();
    }
}