<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Variable\VariableCreateRequest;
use App\Http\Requests\Backend\Variable\VariableUpdateRequest;
use App\Http\Requests\Backend\Variable\VariableValueUpdateRequest;
use App\Models\Variable;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;

/**
 * Class VariableController
 * @package App\Http\Controllers\Backend
 */
class VariableController extends BackendController
{

    /**
     * @var string
     */
    public $module = "variable";

    /**
     * @var array
     */
    public $accessMap = [
        'indexValues' => 'variable.value.read',
        'updateValue' => 'variable.value.write',

        'index'   => 'variable.read',
        'create'  => 'variable.create',
        'store'   => 'variable.create',
        'show'    => 'variable.read',
        'edit'    => 'variable.read',
        'update'  => 'variable.write',
        'destroy' => 'variable.delete',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.variables'));
    }

    /**
     * Display a listing of the resource.
     * GET /variable
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Variable::select(['id', 'name', 'description', 'type', 'key', 'multilingual']);

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'variables.id', '=', '$1')
                ->editColumn(
                    'type',
                    function ($model) {
                        return trans('labels.variable_type_'.$model->getStringType());
                    }
                )
                ->editColumn(
                    'multilingual',
                    function ($model) {
                        return view(
                            'partials.datatables.icon_status',
                            ['model' => $model, 'type' => $this->module, 'field' => 'multilingual']
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
                ->removeColumn('text')
                ->removeColumn('translation')
                ->make();
        }

        $this->data('page_title', trans('labels.variables'));
        $this->breadcrumbs(trans('labels.variables_list'));

        return $this->render('views.variable.index');
    }

    public function indexValues()
    {
        $this->data('page_title', trans('labels.variables'));
        $this->breadcrumbs(trans('labels.variables_list'));

        $list = Variable::all();

        $this->data('list', $list);

        return $this->render('views.variable.index_values');
    }

    /**
     * Show the form for creating a new resource.
     * GET /variable/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Variable());

        $this->data('page_title', trans('labels.variable_create'));

        $this->breadcrumbs(trans('labels.variable_create'));

        $this->_fillAdditionTemplateData();

        return $this->render('views.variable.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /variable
     *
     * @param VariableCreateRequest $request
     *
     * @return \Response
     */
    public function store(VariableCreateRequest $request)
    {
        $input = $request->only(['type', 'key', 'name', 'description', 'multilingual']);

        try {
            Variable::create($input);

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.variable.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
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
     * GET /variable/{id}/edit
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Variable::withTranslations()->findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.variable_editing'));

            $this->_fillAdditionTemplateData();

            return $this->render('views.variable.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.variable.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /variable/{id}
     *
     * @param int                   $id
     * @param VariableUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, VariableUpdateRequest $request)
    {
        $input = $request->only(['type', 'key', 'name', 'description', 'multilingual']);

        try {
            $model = Variable::findOrFail($id);

            $model->update($input);

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.variable.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());
        }

        return Redirect::back();
    }

    /**
     * Update the specified resource in storage.
     * PUT /variable/{id}
     *
     * @param VariableValueUpdateRequest $request
     *
     * @return \Response
     */
    public function updateValue(VariableValueUpdateRequest $request)
    {
        try {
            $model = Variable::findOrFail($request->get('variable_id'));

            $model->update($request->except('type', 'key', 'name', 'description', 'multilingual'));

            return [
                'status' => 'success',
                'message' => trans('messages.save_ok'),
            ];
        } catch (ModelNotFoundException $e) {
            $message = trans('messages.record_not_found');
        } catch (Exception $e) {
            $message = trans('messages.update_error').': '.$e->getMessage();
        }

        return [
            'status' => 'error',
            'message' => $message,
        ];
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /variable/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Variable::findOrFail($id);

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

        return Redirect::route('admin.variable.index');
    }

    /**
     * set to template addition variables for add\update variable
     */
    private function _fillAdditionTemplateData()
    {
        $types = [];
        foreach (Variable::$types as $key => $type) {
            $types[$key] = trans('labels.variable_type_'.$type);
        }
        $this->data('types', $types);
    }
}