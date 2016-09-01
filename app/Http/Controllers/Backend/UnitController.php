<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Unit\UnitCreateRequest;
use App\Http\Requests\Backend\Unit\UnitUpdateRequest;
use App\Models\Unit;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class UnitController
 * @package App\Http\Controllers\Backend
 */
class UnitController extends BackendController
{

    /**
     * @var string
     */
    public $module = "unit";

    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'unit.read',
        'create'  => 'unit.create',
        'store'   => 'unit.create',
        'show'    => 'unit.read',
        'edit'    => 'unit.read',
        'update'  => 'unit.write',
        'destroy' => 'unit.delete',
    ];

    /**
     * @var Unit
     */
    public $model;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.units'));

        $this->breadcrumbs(trans('labels.units'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /unit
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Unit::with('ingredients')->select('id', 'name', 'position');

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'units.id', '=', '$1')
                ->filterColumn('name', 'where', 'units.name', 'LIKE', '%$1%')
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            [
                                'model'           => $model,
                                'type'            => $this->module,
                                'delete_function' => $model->ingredients->count() ?
                                    'delete_unit('.$model->id.')' :
                                    false,
                            ]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('ingredients')
                ->make();
        }

        $this->data('page_title', trans('labels.units'));
        $this->breadcrumbs(trans('labels.units_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /unit/create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->data('model', new Unit);

        $this->data('page_title', trans('labels.unit_create'));

        $this->breadcrumbs(trans('labels.unit_create'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /unit
     *
     * @param UnitCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UnitCreateRequest $request)
    {
        try {
            $model = new Unit($request->all());

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
     * GET /unit/{id}
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
     * GET /unit/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {
            $model = Unit::findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.unit_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /unit/{id}
     *
     * @param  int              $id
     * @param UnitUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UnitUpdateRequest $request)
    {
        try {
            $model = Unit::findOrFail($id);

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
     * @param int $unit_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeleteForm($unit_id)
    {
        return response()->json(
            [
                'title'   => trans('labels.deleting_record'),
                'message' => view('views.'.$this->module.'.partials.delete_message', ['unit_id' => $unit_id])
                    ->render(),
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /unit/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Unit::findOrFail($id);

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