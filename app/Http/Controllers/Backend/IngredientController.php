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
use App\Services\IngredientService;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
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
        'index'           => 'ingredient.read',
        'indexIncomplete' => 'ingredient.read',
        'create'          => 'ingredient.create',
        'store'           => 'ingredient.create',
        'show'            => 'ingredient.read',
        'edit'            => 'ingredient.read',
        'update'          => 'ingredient.write',
        'destroy'         => 'ingredient.delete',
    ];
    
    /**
     * @var array
     */
    public $filter_types = ['category', 'supplier', 'unit', 'sale_unit'];
    
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
     * @var array
     */
    private $parameters;
    
    /**
     * @var array
     */
    private $nutritional_values;
    
    /**
     * @var \App\Services\IngredientService
     */
    private $ingredientService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\IngredientService               $ingredientService
     */
    public function __construct(ResponseFactory $response, IngredientService $ingredientService)
    {
        parent::__construct($response);
        
        $this->ingredientService = $ingredientService;
        
        Meta::title(trans('labels.ingredients'));
        
        $this->breadcrumbs(trans('labels.ingredients'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /ingredient
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            return $this->ingredientService->table();
        }
        
        $this->data('page_title', trans('labels.ingredients'));
        $this->breadcrumbs(trans('labels.ingredients_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function indexIncomplete(Request $request)
    {
        $this->_fillAdditionalTemplateData();
        
        if ($request->get('draw')) {
            $this->ingredientService->setData('categories', $this->categories);
            $this->ingredientService->setData('units', $this->units);
            $this->ingredientService->setData('suppliers', $this->suppliers);
            $this->ingredientService->setData('filter_types', $this->filter_types);
            
            return $this->ingredientService->tableIncomplete($request);
        }
        
        $this->data('page_title', trans('labels.incomplete_ingredients'));
        $this->breadcrumbs(trans('labels.incomplete_ingredients_list'));
        
        return $this->render('views.'.$this->module.'.index_incomplete');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /ingredient/create
     *
     * @return \Illuminate\Contracts\View\View
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
     * Show the form for quick creating a new resource.
     * GET /ingredient/quick-create
     *
     * @return array
     */
    public function quickCreate()
    {
        try {
            $model = new Ingredient();
            
            $this->_fillAdditionalTemplateData($model);
            
            return [
                'status' => 'success',
                'html'   => view('ingredient.popups.quick_create')->with(
                    [
                        'model'              => $model,
                        'units'              => $this->units,
                        'categories'         => $this->categories,
                        'suppliers'          => $this->suppliers,
                        'parameters'         => $this->parameters,
                        'nutritional_values' => $this->nutritional_values,
                    ]
                )->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /ingredient
     *
     * @param IngredientRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(IngredientRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $input = $this->ingredientService->prepareInputData($request);
            
            $model = new Ingredient($input);
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
     * Store a newly quick created resource in storage.
     * POST /ingredient
     *
     * @param IngredientRequest $request
     *
     * @return array
     */
    public function quickStore(IngredientRequest $request)
    {
        try {
            DB::beginTransaction();
    
            $input = $this->ingredientService->prepareInputData($request);
            
            $model = new Ingredient($input);
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            DB::commit();
            
            return [
                'status'     => 'success',
                'ingredient' => $model,
                'message'    => trans('messages.ingredient successfully added'),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * Display the specified resource.
     * GET /ingredient/{id}
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
     * GET /ingredient/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, IngredientRequest $request)
    {
        try {
            $model = Ingredient::with('parameters', 'nutritional_values')->whereId($id)->firstOrFail();
            
            DB::beginTransaction();
            
            $input = $this->ingredientService->prepareInputData($request);
            
            $model->update($input);
            
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
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function find(Request $request)
    {
        try {
            $ingredients = Ingredient::where('name', 'like', '%'.$request->get('text', '').'%');
            
            if ($request->get('in_sales', false)) {
                $ingredients->inSales();
            }
            
            return response()->json($ingredients->get());
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     *  fill additional template data
     *
     * @param \App\Models\Ingredient|null $model
     */
    private function _fillAdditionalTemplateData($model = null)
    {
        $this->units = ['' => trans('labels.please_select')];
        foreach (Unit::positionSorted()->get() as $unit) {
            $this->units[$unit->id] = $unit->name;
        }
        $this->data('units', $this->units);
        
        $this->categories = ['' => trans('labels.please_select')];
        foreach (Category::positionSorted()->get() as $category) {
            $this->categories[$category->id] = $category->name;
        }
        $this->data('categories', $this->categories);
        
        $this->suppliers = ['' => trans('labels.please_select')];
        foreach (Supplier::prioritySorted()->get() as $supplier) {
            $this->suppliers[$supplier->id] = $supplier->name;
        }
        $this->data('suppliers', $this->suppliers);
        
        if ($model) {
            $this->parameters = Parameter::positionSorted()->get();
            $this->data('parameters', $this->parameters);
            $this->data('selected_parameters', $model->parameters->keyBy('id')->toArray());
            
            $this->nutritional_values = NutritionalValue::positionSorted()->get();
            $this->data('nutritional_values', $this->nutritional_values);
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
        $model->parameters()->sync((array) $request->get('additional_parameter', []));
        
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
}