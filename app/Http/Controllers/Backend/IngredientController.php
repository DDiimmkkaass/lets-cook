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
     * @var Ingredient
     */
    public $model;
    
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
    private function _fillAdditionalTemplateData(Ingredient $model)
    {
        $units = ['' => trans('labels.please_select')];
        foreach (Unit::all() as $unit) {
            $units[$unit->id] = $unit->name;
        }
        $this->data('units', $units);

        $categories = ['' => trans('labels.please_select')];
        foreach (Category::all() as $category) {
            $categories[$category->id] = $category->name;
        }
        $this->data('categories', $categories);

        $suppliers = ['' => trans('labels.please_select')];
        foreach (Supplier::all() as $supplier) {
            $suppliers[$supplier->id] = $supplier->name;
        }
        $this->data('suppliers', $suppliers);

        $this->data('parameters', Parameter::positionSorted()->get());
        $this->data('selected_parameters', $model->parameters->keyBy('id')->toArray());

        $this->data('nutritional_values', NutritionalValue::positionSorted()->get());
        $this->data(
            'ingredient_nutritional_values',
            $model->nutritional_values->keyBy('nutritional_value_id')->toArray()
        );
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
}