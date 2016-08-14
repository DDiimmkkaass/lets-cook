<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\TagCategory\TagCategoryCreateRequest;
use App\Http\Requests\Backend\TagCategory\TagCategoryUpdateRequest;
use App\Models\TagCategory;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class TagCategoryController
 * @package App\Http\Controllers\Backend
 */
class TagCategoryController extends BackendController
{

    /**
     * @var string
     */
    public $module = "tag_category";

    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'tagcategory.read',
        'create'  => 'tagcategory.create',
        'store'   => 'tagcategory.create',
        'show'    => 'tagcategory.read',
        'edit'    => 'tagcategory.read',
        'update'  => 'tagcategory.write',
        'destroy' => 'tagcategory.delete',
    ];

    /**
     * @var TagCategory
     */
    public $model;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.tag_categories'));

        $this->breadcrumbs(trans('labels.tag_categories'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /tag_category
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = TagCategory::select('id', 'name', 'position');

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'tag_categories.id', '=', '$1')
                ->filterColumn('name', 'where', 'tag_categories.name', 'LIKE', '%$1%')
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            [
                                'model'           => $model,
                                'type'            => $this->module,
                            ]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->make();
        }

        $this->data('page_title', trans('labels.tag_categories'));
        $this->breadcrumbs(trans('labels.tag_categories_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /tag_category/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new TagCategory);

        $this->data('page_title', trans('labels.tag_category_creating'));

        $this->breadcrumbs(trans('labels.tag_category_creating'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /tag_category
     *
     * @param TagCategoryCreateRequest $request
     *
     * @return \Response
     */
    public function store(TagCategoryCreateRequest $request)
    {
        try {
            $model = new TagCategory($request->all());

            $model->save();

            FlashMessages::add('success', trans('messages.save_ok'));

            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.save_failed'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /tag_category/{id}
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
     * GET /tag_category/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = TagCategory::findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.tag_category_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /tag_category/{id}
     *
     * @param  int                  $id
     * @param TagCategoryUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, TagCategoryUpdateRequest $request)
    {
        try {
            $model = TagCategory::findOrFail($id);

            $model->update($request->all());

            FlashMessages::add('success', trans('messages.save_ok'));

            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.update_error'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /tag_category/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = TagCategory::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error'));
        }

        return redirect()->route('admin.'.$this->module.'.index');
    }
}