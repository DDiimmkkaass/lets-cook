<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Category\CategoryCreateRequest;
use App\Http\Requests\Backend\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\Ingredient;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Backend
 */
class CategoryController extends BackendController
{

    /**
     * @var string
     */
    public $module = "category";

    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'category.read',
        'create'  => 'category.create',
        'store'   => 'category.create',
        'show'    => 'category.read',
        'edit'    => 'category.read',
        'update'  => 'category.write',
        'destroy' => 'category.delete',
    ];

    /**
     * @var Category
     */
    public $model;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.categories'));

        $this->breadcrumbs(trans('labels.categories'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /category
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Category::with('ingredients')->select('id', 'name', 'position');

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'categories.id', '=', '$1')
                ->filterColumn('name', 'where', 'categories.name', 'LIKE', '%$1%')
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            [
                                'model'           => $model,
                                'type'            => $this->module,
                                'delete_function' => $model->ingredients->count() ?
                                    'delete_category('.$model->id.')' :
                                    false,
                            ]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('ingredients')
                ->make();
        }

        $this->data('page_title', trans('labels.categories'));
        $this->breadcrumbs(trans('labels.categories_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /category/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Category);

        $this->data('page_title', trans('labels.category_creating'));

        $this->breadcrumbs(trans('labels.category_creating'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /category
     *
     * @param CategoryCreateRequest $request
     *
     * @return \Response
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $model = new Category($request->all());

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
     * GET /category/{id}
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
     * GET /category/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Category::findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.category_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /category/{id}
     *
     * @param  int                  $id
     * @param CategoryUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, CategoryUpdateRequest $request)
    {
        try {
            $model = Category::findOrFail($id);

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
     * @param int $category_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeleteForm($category_id)
    {
        return response()->json(
            [
                'title'   => trans('labels.deleting_record'),
                'message' => view('views.'.$this->module.'.partials.delete_message', ['category_id' => $category_id])
                    ->render(),
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /category/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Category::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error'));
        }

        return redirect()->route('admin.'.$this->module.'.index');
    }

    /**
     * @param int $category_id
     *
     * @return array
     */
    public function completedIngredients($category_id)
    {
        try {
            $ingredients = Ingredient::completed()
                ->whereCategoryId($category_id)
                ->nameSorted()
                ->get()
                ->toArray();

            return [
                'status'      => 'success',
                'ingredients' => $ingredients,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
}