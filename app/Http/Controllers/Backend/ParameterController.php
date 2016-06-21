<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Parameter\ParameterCreateRequest;
use App\Http\Requests\Backend\Parameter\ParameterUpdateRequest;
use App\Models\Parameter;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class ParameterController
 * @package App\Http\Controllers\Backend
 */
class ParameterController extends BackendController
{

    /**
     * @var string
     */
    public $module = "parameter";

    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'parameter.read',
        'create'  => 'parameter.create',
        'store'   => 'parameter.create',
        'show'    => 'parameter.read',
        'edit'    => 'parameter.read',
        'update'  => 'parameter.write',
        'destroy' => 'parameter.delete',
    ];

    /**
     * @var Parameter
     */
    public $model;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.parameters'));

        $this->breadcrumbs(trans('labels.parameters'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /parameter
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Parameter::select('id', 'name', 'position');

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'parameters.id', '=', '$1')
                ->filterColumn('name', 'where', 'parameters.name', 'LIKE', '%$1%')
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

        $this->data('page_title', trans('labels.parameters'));
        $this->breadcrumbs(trans('labels.parameters_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /parameter/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Parameter);

        $this->data('page_title', trans('labels.parameter_create'));

        $this->breadcrumbs(trans('labels.parameter_create'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /parameter
     *
     * @param ParameterCreateRequest $request
     *
     * @return \Response
     */
    public function store(ParameterCreateRequest $request)
    {
        try {
            $model = new Parameter($request->all());

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
     * GET /parameter/{id}
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
     * GET /parameter/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Parameter::findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.parameter_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /parameter/{id}
     *
     * @param  int                  $id
     * @param ParameterUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, ParameterUpdateRequest $request)
    {
        try {
            $model = Parameter::findOrFail($id);

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
     * DELETE /parameter/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Parameter::findOrFail($id);

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