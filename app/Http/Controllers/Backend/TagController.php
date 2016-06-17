<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Tag\TagCreateRequest;
use App\Http\Requests\Backend\Tag\TagUpdateRequest;
use App\Models\Tag;
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
 * Class TagController
 * @package App\Http\Controllers\Backend
 */
class TagController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "tag";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'tag.read',
        'create'          => 'tag.create',
        'store'           => 'tag.create',
        'show'            => 'tag.read',
        'edit'            => 'tag.read',
        'update'          => 'tag.write',
        'destroy'         => 'tag.delete',
        'ajaxFieldChange' => 'tag.write',
    ];

    /**
     * @var Tag
     */
    public $model;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.tags'));

        $this->breadcrumbs(trans('labels.tags'), route('admin.tag.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /tag
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Tag::withTranslations()
                ->joinTranslations('tags', 'tag_translations', 'id', 'tag_id')
                ->select(
                    'tags.id',
                    'tag_translations.name',
                    'status',
                    'position'
                );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'tags.id', '=', '$1')
                ->filterColumn('tag_translations.name', 'where', 'tag_translations.name', 'LIKE', '%$1%')
                ->editColumn(
                    'status',
                    function ($model) {
                        return view(
                            'partials.datatables.toggler',
                            ['model' => $model, 'type' => $this->module, 'field' => 'status']
                        )->render();
                    }
                )
                ->editColumn(
                    'position',
                    function ($model) {
                        return view(
                            'partials.datatables.text_input',
                            ['model' => $model, 'type' => $this->module, 'field' => 'position']
                        )->render();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('meta_keywords')
                ->removeColumn('meta_title')
                ->removeColumn('meta_description')
                ->removeColumn('translations')
                ->make();
        }

        $this->data('page_title', trans('labels.tags'));
        $this->breadcrumbs(trans('labels.tags_list'));

        return $this->render('views.tag.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /tag/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Tag);

        $this->data('page_title', trans('labels.tag_create'));

        $this->breadcrumbs(trans('labels.tag_create'));

        return $this->render('views.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /tag
     *
     * @param TagCreateRequest $request
     *
     * @return \Response
     */
    public function store(TagCreateRequest $request)
    {
        $input = $request->all();

        try {
            $model = new Tag();
            $model->fill($input);

            $model->save();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.tag.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /tag/{id}
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
     * GET /tag/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Tag::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.tag.index');
        }

        $this->data('page_title', '"'.$model->name.'"');

        $this->breadcrumbs(trans('labels.tag_editing'));

        return $this->render('views.tag.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /tag/{id}
     *
     * @param  int             $id
     * @param TagUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, TagUpdateRequest $request)
    {
        try {
            $model = Tag::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.tag.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->update($input);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.tag.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /tag/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Tag::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.tag.index');
    }
}