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
use App\Models\Page;
use App\Models\User;
use App\Transformers\CommentTransformer;
use Illuminate\Support\Collection;
use Sentry;

/**
 * Class CommentService
 * @package App\Services
 */
class CommentService
{
    
    /**
     * @param \App\Http\Requests\Frontend\Comment\CommentCreateRequest $request
     * @param \App\Models\User                                         $user
     *
     * @return array
     */
    public function prepareInput(CommentCreateRequest $request, User $user)
    {
        $input = [];
            
        $input['comment'] = $request->get('comment');
        $input['user_id'] = $user->id;
        $input['commentable_type'] = 'page';
        $input['commentable_id'] = Page::whereSlug('home')->first()->id;
        
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
        $comment->status = false;

        $model->comments()->save($comment);

        return $comment;
    }
    
    /**
     * @return array
     */
    public function getList()
    {
        $page = Page::whereSlug('home')->first();
        
        $list = $page->comments()->with('user')->visible()->latest();
    
        if (request('page', 1) == 0) {
            $list = $list->get();
        } else {
            $list = $list->paginate(config('comments.per_page'));
        }
    
        $list = $this->_prepareData($list);
    
        return $list;
    }
    
    /**
     * @param $list
     *
     * @return array
     */
    private function _prepareData($list)
    {
        $data = ['comments' => []];
        
        foreach ($list as $item) {
            $data['comments'][] = CommentTransformer::transform($item);
        }
        
        if ($list instanceof Collection) {
            $data['next_count'] = 0;
        } else {
            if ($list->lastPage() == $list->currentPage()) {
                $data['next_count'] = 0;
            } else {
                $data['next_count'] = $list->total() - $list->currentPage() * $list->perPage();
                $data['next_count'] = $data['next_count'] > $list->perPage() ? $list->perPage() : $data['next_count'];
                
                $data['next_count'] = $data['next_count'] >= 0 ? $data['next_count'] : 0;
            }
        }
        
        $data['next_count_label'] = trans('front_labels.pagination_next').' '.
            $data['next_count'].' '.
            trans_choice('front_labels.count_of_comments', $data['next_count']);
        
        return $data;
    }
}