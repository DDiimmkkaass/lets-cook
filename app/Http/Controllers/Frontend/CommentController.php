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
use Meta;

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
     * @return \Illuminate\Contracts\View\View|array
     */
    public function index(Request $request)
    {
        $list = $this->commentService->getList();
        
        if ($request->ajax()) {
            return $list;
        }
        
        $this->data('list', $list['comments']);
        $this->data('next_count', $list['next_count']);
        
        Meta::canonical(localize_route('comments.index'));
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Comment\CommentCreateRequest $request
     *
     * @return array
     */
    public function store(CommentCreateRequest $request)
    {
        try {
            if (!$this->user) {
                return [
                    'status'  => 'error',
                    'message' => trans('front_messages.this action available only for registered users'),
                ];
            }
            
            $input = $this->commentService->prepareInput($request, $this->user);
            
            $comment = $this->commentService->store($input);
            
            Event::fire(new NewComment($comment));
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.comment successfully added message'),
            ];
        } catch (Exception $e) {
            return ['status'  => 'error',
                    'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
}