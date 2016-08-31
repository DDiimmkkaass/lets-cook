<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Comment\CommentRequest;
use App\Models\Comment;
use App\Models\Page;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class CommentController
 * @package App\Http\Controllers\Backend
 */
class CommentController extends BackendController
{
    
    use AjaxFieldsChangerTrait;
    
    /**
     * @var string
     */
    public $module = "comment";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'comment.read',
        'show'            => 'comment.read',
        'edit'            => 'comment.read',
        'update'          => 'comment.write',
        'destroy'         => 'comment.delete',
        'ajaxFieldChange' => 'comment.write',
    ];
    
    /**
     * @var Comment
     */
    public $model;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);
        
        Meta::title(trans('labels.comments'));
        
        $this->breadcrumbs(trans('labels.comments'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /comment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Comment::with('user', 'user.info')
                ->select(
                    'id',
                    'commentable_id',
                    'commentable_type',
                    'name',
                    'comment',
                    'date',
                    'status'
                );
            
            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'comments.id', '=', '$1')
                ->filterColumn('comment', 'where', 'comments.comment', 'LIKE', '%$1%')
                ->editColumn(
                    'comment',
                    function ($model) {
                        return str_limit($model->comment);
                    }
                )
                ->editColumn(
                    'date',
                    function ($model) {
                        return '<div class="date-line">'.
                        get_localized_date($model->date, 'd-m-Y').
                        '</div>';
                    }
                )
                ->editColumn(
                    'status',
                    function ($model) {
                        return view(
                            'partials.datatables.toggler',
                            ['model' => $model, 'type' => $this->module, 'field' => 'status']
                        )->render();
                    }
                )
                ->addColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('user')
                ->removeColumn('info')
                ->removeColumn('user_id')
                ->removeColumn('commentable_id')
                ->removeColumn('commentable_type')
                ->make();
        }
        
        $this->data('page_title', trans('labels.comments'));
        $this->breadcrumbs(trans('labels.comments_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /comment/create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->data('model', new Comment());
        
        $this->data('page_title', trans('labels.comment_creating'));
        
        $this->breadcrumbs(trans('labels.comment_creating'));
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /comment
     *
     * @param CommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentRequest $request)
    {
        try {
            $page = Page::whereSlug('home')->firstOrFail();
            
            $input = $request->all();
            
            $model = new Comment($input);
            $model->status = $input['status'];
    
            $page->comments()->save($model);
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.save_failed'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     * GET /comment/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return $this->edit($id);
    }
    
    /**
     * Show the form for editing the specified resource.
     * GET /comment/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {
            $model = Comment::findOrFail($id);
            
            $this->data('page_title', trans('labels.comment_editing'));
            
            $this->breadcrumbs(trans('labels.comment_editing'));
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /comment/{id}
     *
     * @param  int           $id
     * @param CommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, CommentRequest $request)
    {
        try {
            $input = $request->all();
            
            $model = Comment::findOrFail($id);
            
            $model->fill($input);
            $model->status = $input['status'];
            
            $model->save();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            DB::rollBack();
            
            FlashMessages::add("error", trans('messages.update_error'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     * DELETE /comment/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Comment::findOrFail($id);
            
            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error'));
        }
        
        return redirect()->route('admin.'.$this->module.'.index');
    }
}