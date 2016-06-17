<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:33
 */

namespace App\Events\Frontend;

use App\Events\Event;
use App\Models\Comment;
use Illuminate\Queue\SerializesModels;

/**
 * Class NewComment
 * @package App\Events\Frontend
 */
class NewComment extends Event
{

    use SerializesModels;

    /**
     * @var \App\Models\Comment
     */
    public $comment;

    /**
     * NewComment constructor.
     *
     * @param \App\Models\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = Comment::with('user', 'user.info')->whereId($comment->id)->first();
    }
}