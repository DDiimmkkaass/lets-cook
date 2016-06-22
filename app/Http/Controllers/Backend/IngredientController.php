<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Ingredient\IngredientRequest;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientNutritionalValue;
use App\Models\NutritionalValue;
use App\Models\Parameter;
use App\Models\Supplier;
use App\Models\Unit;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class IngredientController
 * @package App\Http\Controllers\Backend
 */
class IngredientController extends BackendController
{
    
    use AjaxFieldsChangerTrait;
    
    /**
     * @var string
     */
    public $module = "ingredient";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'ingredient.read',
        'create'  => 'ingredient.create',
        'store'   => 'ingredient.create',
        'show'    => 'ingredient.read',
        'edit'    => 'ingredient.read',
        'update'  => 'ingredient.write',
        'destroy' => 'ingredient.delete',
    ];
    
    /**
     * @var array
     */
    public $filter_types = ['category', 'supplier', 'unit'];
    
    /**
     * @var Ingredient
     */
    public $model;
    
    /**
     * @var array
     */
    private $units;
    
    /**
     * @var array
     */
    private $categories;
    
    /**
     * @var array
     */
    private $suppliers;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);
        
        Meta::title(trans('labels.ingredients'));
        
        $this->breadcrumbs(trans('labels.ingredients'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /ingredient
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Ingredient::joinCategory()->joinSupplier()->joinUnit()
                ->select(
                    'ingredients.id',
                    'ingredients.name',
                    'ingredients.image',
                    DB::raw('categories.name as category'),
                    DB::raw('units.name as unit'),
                    DB::raw('suppliers.name as supplier'),
                    'price'
                );
            
            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'ingredients.id', '=', '$1')
                ->filterColumn('name', 'where', 'ingredients.name', 'LIKE', '%$1%')
                ->filterColumn('category', 'where', 'categories.name', 'LIKE', '%$1%')
                ->filterColumn('unit', 'where', 'units.name', 'LIKE', '%$1%')
                ->filterColumn('supplier', 'where', 'suppliers.name', 'LIKE', '%$1%')
                ->editColumn(
                    'name',
                    function ($model) {
                        $html = $model->name;
                        
                        if ($model->image) {
                            $html = view(
                                    'partials.image',
                                    [
                                        'src'        => $model->image,
                                        'attributes' => ['width' => 50, 'class' => 'margin-right-10'],
                                    ]
                                )->render().$html;
                        }
                        
                        return $html;
                    }
                )
                ->editColumn(
                    'price',
                    function ($model) {
                        return $model->price.' '.$this->currency;
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
                ->removeColumn('image')
                ->make();
        }
        
        $this->data('page_title', trans('labels.ingredients'));
        $this->breadcrumbs(trans('labels.ingredients_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return $this|array|\Bllim\Datatables\json
     */
    public function indexIncomplete(Request $request)
    {
        $this->_fillAdditionalTemplateData();
        
        if ($request->get('draw')) {
            
            $list = Ingredient::joinCategory()->joinSupplier()->joinUnit()
                ->select(
                    'ingredients.id',
                    'ingredients.name',
                    'ingredients.image',
                    DB::raw('categories.name as category'),
                    DB::raw('units.name as unit'),
                    DB::raw('suppliers.name as supplier'),
                    'category_id',
                    'unit_id',
                    'supplier_id'
                );
            
            $this->_implodeIncompleteFilters($list);
            
            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'ingredients.id', '=', '$1')
                ->filterColumn('name', 'where', 'ingredients.name', 'LIKE', '%$1%')
                ->filterColumn('category', 'where', 'categories.name', 'LIKE', '%$1%')
                ->filterColumn('unit', 'where', 'units.name', 'LIKE', '%$1%')
                ->filterColumn('supplier', 'where', 'suppliers.name', 'LIKE', '%$1%')
                ->editColumn(
                    'name',
                    function ($model) {
                        $html = link_to_route(
                            'admin.'.$this->module.'.show',
                            $model->name,
                            ['ingredient' => $model->id],
                            ['target' => '_blank']
                        );
                        
                        if ($model->image) {
                            $html = view(
                                    'partials.image',
                                    [
                                        'src'        => $model->image,
                                        'attributes' => ['width' => 50, 'class' => 'margin-right-10'],
                                    ]
                                )->render().$html;
                        }
                        
                        return $html;
                    }
                )
                ->editColumn(
                    'category',
                    function ($model) use ($request) {
                        if (!$model->category_id || $request->get('category', false)) {
                            $html = view(
                                'partials.datatables.select',
                                [
                                    'list'  => $this->categories,
                                    'field' => 'category_id',
                                    'model' => $model,
                                    'type'  => $this->module,
                                ]
                            )->render();
                        } else {
                            $html = $model->category;
                        }
                        
                        return $html;
                    }
                )
                ->editColumn(
                    'unit',
                    function ($model) use ($request) {
                        if (!$model->unit_id || $request->get('unit', false)) {
                            $html = view(
                                'partials.datatables.select',
                                [
                                    'list'  => $this->units,
                                    'field' => 'unit_id',
                                    'model' => $model,
                                    'type'  => $this->module,
                                ]
                            )->render();
                        } else {
                            $html = $model->unit;
                        }
                        
                        return $html;
                    }
                )
                ->editColumn(
                    'supplier',
                    function ($model) use ($request) {
                        if (!$model->supplier_id || $request->get('supplier', false)) {
                            $html = view(
                                'partials.datatables.select',
                                [
                                    'list'  => $this->suppliers,
                                    'field' => 'supplier_id',
                                    'model' => $model,
                                    'type'  => $this->module,
                                ]
                            )->render();
                        } else {
                            $html = $model->supplier;
                        }
                        
                        return $html;
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
                ->removeColumn('image')
                ->removeColumn('category_id')
                ->removeColumn('unit_id')
                ->removeColumn('supplier_id')
                ->make();
        }
        
        $this->data('page_title', trans('labels.incomplete_ingredients'));
        $this->breadcrumbs(trans('labels.incomplete_ingredients_list'));
        
        return $this->render('views.'.$this->module.'.index_incomplete');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /ingredient/create
     *
     * @return Response
     */
    public function create()
    {
        $model = new Ingredient();
        
        $this->data('model', $model);
        
        $this->data('page_title', trans('labels.ingredient_creating'));
        
        $this->breadcrumbs(trans('labels.ingredient_creating'));
        
        $this->_fillAdditionalTemplateData($model);
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /ingredient
     *
     * @param IngredientRequest $request
     *
     * @return \Response
     */
    public function store(IngredientRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $model = new Ingredient($request->all());
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            DB::rollBack();
            
            FlashMessages::add('error', trans('messages.save_failed'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     * GET /ingredient/{id}
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
     * GET /ingredient/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Ingredient::with('parameters', 'nutritional_values')->whereId($id)->firstOrFail();
            
            $this->data('page_title', '"'.$model->name.'"');
            
            $this->breadcrumbs(trans('labels.ingredient_editing'));
            
            $this->_fillAdditionalTemplateData($model);
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /ingredient/{id}
     *
     * @param  int              $id
     * @param IngredientRequest $request
     *
     * @return \Response
     */
    public function update($id, IngredientRequest $request)
    {
        try {
            $model = Ingredient::with('parameters', 'nutritional_values')->whereId($id)->firstOrFail();
            
            DB::beginTransaction();
            
            $model->update($request->all());
            
            $this->_saveRelationships($model, $request);
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            DB::rollBack();
            
            dd('message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile());
            
            FlashMessages::add("error", trans('messages.update_error'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     * DELETE /ingredient/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Ingredient::findOrFail($id);
            
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
     *  fill additional template data
     *
     * @param \App\Models\Ingredient|null $model
     */
    private function _fillAdditionalTemplateData($model = null)
    {
        $this->units = ['' => trans('labels.please_select')];
        foreach (Unit::all() as $unit) {
            $this->units[$unit->id] = $unit->name;
        }
        $this->data('units', $this->units);
        
        $this->categories = ['' => trans('labels.please_select')];
        foreach (Category::all() as $category) {
            $this->categories[$category->id] = $category->name;
        }
        $this->data('categories', $this->categories);
        
        $this->suppliers = ['' => trans('labels.please_select')];
        foreach (Supplier::all() as $supplier) {
            $this->suppliers[$supplier->id] = $supplier->name;
        }
        $this->data('suppliers', $this->suppliers);
        
        if ($model) {
            $this->data('parameters', Parameter::positionSorted()->get());
            $this->data('selected_parameters', $model->parameters->keyBy('id')->toArray());
            
            $this->data('nutritional_values', NutritionalValue::positionSorted()->get());
            $this->data(
                'ingredient_nutritional_values',
                $model->nutritional_values->keyBy('nutritional_value_id')->toArray()
            );
        }
        
        $filter_types = ['' => trans('labels.please_select')];
        foreach ($this->filter_types as $type) {
            $filter_types[$type] = trans('labels.'.$type);
        }
        $this->data('filter_types', $filter_types);
    }
    
    /**
     * @param \App\Models\Ingredient   $model
     * @param \Illuminate\Http\Request $request
     */
    private function _saveRelationships(Ingredient $model, Request $request)
    {
        $model->parameters()->sync($request->get('parameters', []));
        
        foreach ($request->get('nutritional_values', []) as $nutritional_value) {
            $_model = IngredientNutritionalValue::firstOrNew(
                [
                    'ingredient_id'        => $model->id,
                    'nutritional_value_id' => $nutritional_value['id'],
                ]
            );
            
            $_model->value = $nutritional_value['value'];
            
            $_model->save();
        }
    }
    
    /**
     * @param $list
     */
    private function _implodeIncompleteFilters(&$list)
    {
        $filter = request()->get('filter', false);
        if ($filter && in_array($filter, $this->filter_types)) {
            $list->whereNull('ingredients.'.$filter.'_id');
        }
        
        $types_values = request()->only($this->filter_types, []);
        foreach ($types_values as $type => $value) {
            if (!is_null($value)) {
                $list->where('ingredients.'.$type.'_id', $value);
                
                $filter = true;
            }
        }
        
        if (!$filter) {
            foreach ($this->filter_types as $type) {
                $list->orWhereNull('ingredients.'.$type.'_id');
            }
        }
    }
}