<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Supplier\SupplierCreateRequest;
use App\Http\Requests\Backend\Supplier\SupplierUpdateRequest;
use App\Models\Supplier;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class SupplierController
 * @package App\Http\Controllers\Backend
 */
class SupplierController extends BackendController
{
    
    /**
     * @var string
     */
    public $module = "supplier";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'supplier.read',
        'create'  => 'supplier.create',
        'store'   => 'supplier.create',
        'show'    => 'supplier.read',
        'edit'    => 'supplier.read',
        'update'  => 'supplier.write',
        'destroy' => 'supplier.delete',
    ];
    
    /**
     * @var Supplier
     */
    public $model;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);
        
        Meta::title(trans('labels.suppliers'));
        
        $this->breadcrumbs(trans('labels.suppliers'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /supplier
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Supplier::with('ingredients')->select('id', 'name', 'comments', 'priority');
            
            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'suppliers.id', '=', '$1')
                ->filterColumn('name', 'where', 'suppliers.name', 'LIKE', '%$1%')
                ->editColumn(
                    'comments',
                    function ($model) {
                        return str_limit($model->comments, 150);
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            [
                                'model'           => $model,
                                'type'            => $this->module,
                                'delete_function' => $model->ingredients->count() ?
                                    'delete_supplier('.$model->id.')' :
                                    false,
                            ]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('ingredients')
                ->make();
        }
        
        $this->data('page_title', trans('labels.suppliers'));
        $this->breadcrumbs(trans('labels.suppliers_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /supplier/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Supplier);
        
        $this->data('page_title', trans('labels.supplier_create'));
        
        $this->breadcrumbs(trans('labels.supplier_create'));
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /supplier
     *
     * @param SupplierCreateRequest $request
     *
     * @return \Response
     */
    public function store(SupplierCreateRequest $request)
    {
        try {
            $model = new Supplier($request->all());
            
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
     * GET /supplier/{id}
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
     * GET /supplier/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Supplier::findOrFail($id);
            
            $this->data('page_title', '"'.$model->name.'"');
            
            $this->breadcrumbs(trans('labels.supplier_editing'));
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /supplier/{id}
     *
     * @param  int                  $id
     * @param SupplierUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, SupplierUpdateRequest $request)
    {
        try {
            $model = Supplier::findOrFail($id);
            
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
     * @param int $supplier_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeleteForm($supplier_id)
    {
        return response()->json(
            [
                'title'   => trans('labels.deleting_record'),
                'message' => view('views.'.$this->module.'.partials.delete_message', ['supplier_id' => $supplier_id])
                    ->render(),
            ]
        );
    }
    
    /**
     * Remove the specified resource from storage.
     * DELETE /supplier/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Supplier::findOrFail($id);
            
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