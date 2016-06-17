<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\NewComment;
use App\Http\Requests\Frontend\Comment\CommentCreateRequest;
use App\Services\CommentService;
use Event;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CommentController
 * @package App\Http\Controllers\Frontend
 */
class CommentController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'comment';
    
    /**
     * @var \App\Services\CommentService
     */
    protected $commentService;
    
    /**
     * CommentController constructor.
     *
     * @param \App\Services\CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        parent::__construct();
        
        $this->commentService = $commentService;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function index(Request $request)
    {
        try {
            $input = $request->all();
            
            $result = $this->commentService->getComments(
                $input['type'],
                $input['id'],
                $input['page']
            );
            
            $html = '';
            $model = null;
            
            $result['list']->map(
                function ($item) use (&$html, &$model) {
                    $model = $model === null ? $item->getParent() : $model;

                    $html .= view('comments.partials.item')->with(
                        [
                            'item'  => $item,
                            'model' => $model,
                        ]
                    )->render();
                }
            );
            
            $result = [
                'status'                  => 'success',
                'html'                    => $html,
                'button_text'             => trans('labels.show_more').' '.
                    $result['available_comment_count'].' '.
                    trans_choice('labels.comments_label_on_more_button', $result['available_comment_count']),
                'available_comment_count' => $result['available_comment_count'],
                'message'                 => $result['available_comment_count'] < 0 ?
                    trans('messages.no any available comments') : '',
            ];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => trans('messages.an error has occurred, try_later')];
        }
        
        return $result;
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Comment\CommentCreateRequest $request
     *
     * @return array
     */
    public function store(CommentCreateRequest $request)
    {
        try {
            $input = $this->commentService->prepareInput($request);
            
            $comment = $this->commentService->store($input);
            
            Event::fire(new NewComment($comment));

            return [
                'status'     => 'success',
                'message'    => trans('messages.comment successfully added message'),
                'comment'    => view('comments.partials.item')->with(
                    [
                        'item'  => $comment,
                        'model' => $comment->getParent(),
                    ]
                )->render(),
                'parent_id'  => $comment->parent_id,
                'comment_id' => $comment->id,
            ];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => trans('messages.an error has occurred, try_later')];
        }
    }
}