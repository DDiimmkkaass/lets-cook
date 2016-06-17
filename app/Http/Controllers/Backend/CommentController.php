<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Comment\CommentUpdateRequest;
use App\Models\Comment;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
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

        $this->breadcrumbs(trans('labels.comments'), route('admin.comment.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /comment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Comment::with('user', 'user.info')
                ->select(
                    'id',
                    'name',
                    'user_id',
                    'commentable_id',
                    'commentable_type',
                    'comment',
                    'created_at',
                    'status'
                );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'comments.id', '=', '$1')
                ->filterColumn('name', 'where', 'name', 'LIKE', '%$1%')
                ->filterColumn('comment', 'where', 'comment', 'LIKE', '%$1%')
                ->editColumn(
                    'name',
                    function ($model) {
                        return empty($model->user) ?
                            $model->name :
                            link_to(
                                route('admin.user.show', $model->user_id),
                                $model->user->info->name,
                                [
                                    'title' => trans('labels.go_to_user'),
                                ]
                            )->toHtml();
                    }
                )
                ->editColumn(
                    'commentable_id',
                    function ($model) {
                        return link_to(
                            $model->getCommentableItemLink(),
                            $model->getCommentableItemTitle(),
                            [
                                'title' => trans('labels.go_to_item'),
                            ]
                        )->toHtml();
                    }
                )
                ->editColumn(
                    'comment',
                    function ($model) {
                        return str_limit($model->comment);
                    }
                )
                ->editColumn(
                    'created_at',
                    function ($model) {
                        return '<div class="date-line">'.get_localized_date(
                            $model->created_at,
                            'Y-m-d H:i:s',
                            'H:i'
                        ).'</div>';
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
                            ['model' => $model, 'type' => $this->module, 'front_link' => true]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('user')
                ->removeColumn('info')
                ->removeColumn('user_id')
                ->removeColumn('commentable_type')
                ->make();
        }

        $this->data('page_title', trans('labels.comments'));
        $this->breadcrumbs(trans('labels.comments_list'));

        return $this->render('views.comment.index');
    }

    /**
     * Display the specified resource.
     * GET /comment/{id}
     *
     * @param  int $id
     *
     * @return Response
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
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Comment::findOrFail($id);

            $this->data('page_title', trans('labels.comment_editing'));

            $this->breadcrumbs(trans('labels.comment_editing'));

            return $this->render('views.comment.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.comment.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /comment/{id}
     *
     * @param  int                 $id
     * @param CommentUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, CommentUpdateRequest $request)
    {
        try {
            $input = $request->all();

            $model = Comment::findOrFail($id);

            $model->fill($input);
            $model->status = $input['status'];

            $model->save();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.comment.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.comment.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
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
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.comment.index');
    }
}