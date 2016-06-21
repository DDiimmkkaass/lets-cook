<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.06.16
 * Time: 12:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\NutritionalValue\NutritionalValueCreateRequest;
use App\Http\Requests\Backend\NutritionalValue\NutritionalValueUpdateRequest;
use App\Models\NutritionalValue;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class NutritionalValueController
 * @package App\Http\Controllers\Backend
 */
class NutritionalValueController extends BackendController
{

    /**
     * @var string
     */
    public $module = "nutritional_value";

    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'nutritionalvalue.read',
        'create'  => 'nutritionalvalue.create',
        'store'   => 'nutritionalvalue.create',
        'show'    => 'nutritionalvalue.read',
        'edit'    => 'nutritionalvalue.read',
        'update'  => 'nutritionalvalue.write',
        'destroy' => 'nutritionalvalue.delete',
    ];

    /**
     * @var NutritionalValue
     */
    public $model;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.nutritional_values'));

        $this->breadcrumbs(trans('labels.nutritional_values'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /nutritional_value
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = NutritionalValue::select('id', 'name', 'position');

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'nutritional_values.id', '=', '$1')
                ->filterColumn('name', 'where', 'nutritional_values.name', 'LIKE', '%$1%')
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
                ->make();
        }

        $this->data('page_title', trans('labels.nutritional_values'));
        $this->breadcrumbs(trans('labels.nutritional_values_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /nutritional_value/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new NutritionalValue);

        $this->data('page_title', trans('labels.nutritional_value_create'));

        $this->breadcrumbs(trans('labels.nutritional_value_create'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /nutritional_value
     *
     * @param NutritionalValueCreateRequest $request
     *
     * @return \Response
     */
    public function store(NutritionalValueCreateRequest $request)
    {
        try {
            $model = new NutritionalValue($request->all());

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
     * GET /nutritional_value/{id}
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
     * GET /nutritional_value/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = NutritionalValue::findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.nutritional_value_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /nutritional_value/{id}
     *
     * @param  int                  $id
     * @param NutritionalValueUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, NutritionalValueUpdateRequest $request)
    {
        try {
            $model = NutritionalValue::findOrFail($id);

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
     * DELETE /nutritional_value/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = NutritionalValue::findOrFail($id);

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