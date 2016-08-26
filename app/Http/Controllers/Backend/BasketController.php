<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Exceptions\UnExistedBasketTypeException;
use App\Http\Requests\Backend\Basket\BasketCreateRequest;
use App\Http\Requests\Backend\Basket\BasketUpdateRequest;
use App\Models\Basket;
use App\Models\Recipe;
use App\Services\BasketService;
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
 * Class BasketController
 * @package App\Http\Controllers\Backend
 */
class BasketController extends BackendController
{
    
    /**
     * @var string
     */
    protected $type = 'basic';
    
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
     * @var \App\Services\BasketService
     */
    private $basketService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\BasketService                   $basketService
     *
     * @throws \App\Exceptions\UnExistedBasketTypeException
     */
    public function __construct(ResponseFactory $response, BasketService $basketService)
    {
        parent::__construct($response);
        
        $this->_setBasketType();
        
        $this->basketService = $basketService;
        
        $this->data('type', $this->type);
        
        Meta::title(trans('labels.'.$this->type.'_baskets'));
        
        $this->breadcrumbs(
            trans('labels.'.$this->type.'_baskets'),
            route('admin.'.$this->module.'.index', ['type' => $this->type])
        );
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
            $list = Basket::ofType($this->type)->select('id', 'name', 'position', 'price', 'type');
            
            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'baskets.id', '=', '$1')
                ->filterColumn('name', 'where', 'baskets.name', 'LIKE', '%$1%')
                ->editColumn(
                    'price',
                    function ($model) {
                        return $model->price.' '.currency();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'basket.datatables.control_buttons',
                            [
                                'model'       => $model,
                                'type'        => $this->module,
                                'basket_type' => $this->type,
                            ]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('type')
                ->make();
        }
        
        $this->data('page_title', trans('labels.'.$this->type.'_baskets'));
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
        $model = new Basket();

        $this->data('model', $model);
        
        $this->data('page_title', trans('labels.basket_creating'));
        
        $this->breadcrumbs(trans('labels.basket_creating'));

        $this->_fillAdditionalTemplateData($model);

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
        DB::beginTransaction();
        
        try {
            $model = new Basket($request->all());
            $model->type = Basket::getTypeIdByName($this->type);
            
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index', ['type' => $this->type]);
        } catch (Exception $e) {
            DB::rollBack();
            
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
            $model = Basket::ofType($this->type)->with('recipes')->whereId($id)->firstOrFail();
            
            $this->data('page_title', '"'.$model->name.'"');
            
            $this->breadcrumbs(trans('labels.basket_editing'));

            $this->_fillAdditionalTemplateData($model);
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index', ['type' => $this->type]);
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /basket/{id}
     *
     * @param  int                $id
     * @param BasketUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, BasketUpdateRequest $request)
    {
        DB::beginTransaction();

        try {
            $model = Basket::findOrFail($id);
            
            $model->update($request->all());

            $this->_saveRelationships($model, $request);

            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index', ['type' => $this->type]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index', ['type' => $this->type]);
        } catch (Exception $e) {
            DB::rollBack();

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
        
        return redirect()->route('admin.'.$this->module.'.index', ['type' => $this->type]);
    }

    /**
     * @param int $recipe_id
     *
     * @return array
     */
    public function getRecipeRow($recipe_id)
    {
        try {
            $model = Recipe::visible()->findOrFail($recipe_id);

            return [
                'status' => 'success',
                'html'   => view('views.'.$this->module.'.partials.recipe_row', compact('model'))->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @throws \App\Exceptions\UnExistedBasketTypeException
     */
    private function _setBasketType()
    {
        $type = request('type', $this->type);
        
        foreach (Basket::$types as $_type) {
            if ($_type == $type) {
                $this->type = $type;
                
                return;
            }
        }
        
        throw new UnExistedBasketTypeException();
    }

    /**
     * fill additional template data
     *
     * @param \App\Models\Basket $model
     */
    private function _fillAdditionalTemplateData(Basket $model)
    {
        if ($this->type == 'additional') {
            $recipes = ['' => trans('labels.please_select_recipe')];
            if ($model->exists) {
                foreach ($model->allowed_recipes()->visible()->nameSorted()->get(['id', 'name']) as $item) {
                    $recipes[$item->id] = $item->name;
                }
            } else {
                foreach (Recipe::visible()->nameSorted()->get(['id', 'name']) as $item) {
                    $recipes[$item->id] = $item->name;
                }
            }
            $this->data('recipes', $recipes);
        }
    }
    
    /**
     * @param \App\Models\Basket       $model
     * @param \Illuminate\Http\Request $request
     */
    private function _saveRelationships(Basket $model, Request $request)
    {
        $this->basketService->processRecipes($model, $request->get('recipes', []));
    }
}