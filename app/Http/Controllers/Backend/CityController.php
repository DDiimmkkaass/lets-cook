<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\City\CityCreateRequest;
use App\Http\Requests\Backend\City\CityUpdateRequest;
use App\Models\City;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class CityController
 * @package App\Http\Controllers\Backend
 */
class CityController extends BackendController
{

    /**
     * @var string
     */
    public $module = "city";

    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'city.read',
        'create'  => 'city.create',
        'store'   => 'city.create',
        'show'    => 'city.read',
        'edit'    => 'city.read',
        'update'  => 'city.write',
        'destroy' => 'city.delete',
    ];

    /**
     * @var City
     */
    public $model;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.cities'));

        $this->breadcrumbs(trans('labels.cities'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /city
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = City::select('id', 'name', 'position');

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'cities.id', '=', '$1')
                ->filterColumn('name', 'where', 'cities.name', 'LIKE', '%$1%')
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

        $this->data('page_title', trans('labels.cities'));
        $this->breadcrumbs(trans('labels.cities_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /city/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new City);

        $this->data('page_title', trans('labels.city_create'));

        $this->breadcrumbs(trans('labels.city_create'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /city
     *
     * @param CityCreateRequest $request
     *
     * @return \Response
     */
    public function store(CityCreateRequest $request)
    {
        try {
            $model = new City($request->all());

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
     * GET /city/{id}
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
     * GET /city/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = City::findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.city_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /city/{id}
     *
     * @param  int              $id
     * @param CityUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, CityUpdateRequest $request)
    {
        try {
            $model = City::findOrFail($id);

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
     * DELETE /city/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = City::findOrFail($id);

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