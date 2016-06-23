<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Basket\BasketCreateRequest;
use App\Http\Requests\Backend\Basket\BasketUpdateRequest;
use App\Models\Basket;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class BasketController
 * @package App\Http\Controllers\Backend
 */
class BasketController extends BackendController
{

    /**
     * @var string
     */
    public $module = "basket";

    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'basket.read',
        'create'  => 'basket.create',
        'store'   => 'basket.create',
        'show'    => 'basket.read',
        'edit'    => 'basket.read',
        'update'  => 'basket.write',
        'destroy' => 'basket.delete',
    ];

    /**
     * @var Basket
     */
    public $model;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.baskets'));

        $this->breadcrumbs(trans('labels.baskets'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /basket
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Basket::select('id', 'name', 'position');

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'baskets.id', '=', '$1')
                ->filterColumn('name', 'where', 'baskets.name', 'LIKE', '%$1%')
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

        $this->data('page_title', trans('labels.baskets'));
        $this->breadcrumbs(trans('labels.baskets_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /basket/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Basket);

        $this->data('page_title', trans('labels.basket_creating'));

        $this->breadcrumbs(trans('labels.basket_creating'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /basket
     *
     * @param BasketCreateRequest $request
     *
     * @return \Response
     */
    public function store(BasketCreateRequest $request)
    {
        try {
            $model = new Basket($request->all());

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
     * GET /basket/{id}
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
     * GET /basket/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Basket::findOrFail($id);

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.basket_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /basket/{id}
     *
     * @param  int                  $id
     * @param BasketUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, BasketUpdateRequest $request)
    {
        try {
            $model = Basket::findOrFail($id);

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
     * DELETE /basket/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Basket::findOrFail($id);

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