<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 21.03.16
 * Time: 11:04
 */

namespace App\Services;

use App\Exceptions\CommentableClassNotExistException;
use App\Http\Requests\Frontend\Comment\CommentCreateRequest;
use App\Models\Comment;
use Sentry;

/**
 * Class CommentService
 * @package App\Services
 */
class CommentService
{

    /**
     * @param \App\Http\Requests\Frontend\Comment\CommentCreateRequest $request
     *
     * @return array
     */
    public function prepareInput(CommentCreateRequest $request)
    {
        $input = $request->all('');

        $input['user_id'] = Sentry::getUser() ? Sentry::getUser()->getId() : null;

        if (!empty($input['parent_id'])) {
            $parent_comment = Comment::whereId($input['parent_id'])->child()->first();
            $input['parent_id'] = $parent_comment ? $parent_comment->parent_id : $input['parent_id'];
        }

        return $input;
    }

    /**
     * @param array $input
     *
     * @return Comment
     * @throws \App\Exceptions\CommentableClassNotExistException
     */
    public function store($input)
    {
        $model = '\App\Models\\'.studly_case($input['commentable_type']);

        if (!class_exists($model)) {
            throw new CommentableClassNotExistException(trans('messages.contactable class not exists'));
        }

        $model = $model::whereId($input['commentable_id'])->firstOrFail();

        $comment = new Comment($input);
        $comment->user_id = $input['user_id'];
        $comment->status = true;

        $model->comments()->save($comment);

        return $comment;
    }

    /**
     * @param string $commentable_type
     * @param int    $commentable_id
     * @param int    $page
     *
     * @return array
     */
    public function getComments($commentable_type, $commentable_id, $page = 1)
    {
        $last = config($commentable_type.'.last_comments_count', config('comments.last_comments_count'));
        $load = config($commentable_type.'.load_comments_count', config('comments.load_comments_count'));

        $skip = $page > 1 ? $last + (($page - 2) * $load) : 0;
        $take = $page > 1 ? $load : $last;

        $commentable_type = 'App\\\\Models\\\\'.studly_case($commentable_type);

        $list = Comment::whereRaw('commentable_type = \''.$commentable_type.'\'')
            ->whereCommentableId($commentable_id)
            ->with(
                [
                    'likes',
                    'user',
                    'user.info',
                    'visible_childs',
                    'visible_childs.likes',
                    'visible_childs.visible_childs',
                    'visible_childs.user',
                    'visible_childs.user.info',
                ]
            )
            ->parents()
            ->visible()
            ->latest();

        $count = $list->count() - $skip - $take;
        $count = $count > $load ? $load : $count;

        $list = $list->skip($skip)->take($take)->get();

        return ['list' => $list, 'available_comment_count' => $count];
    }
}