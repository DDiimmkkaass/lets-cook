<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\WeeklyMenu\WeeklyMenuCreateRequest;
use App\Http\Requests\Backend\WeeklyMenu\WeeklyMenuUpdateRequest;
use App\Models\Basket;
use App\Models\WeeklyMenu;
use App\Services\BasketService;
use App\Services\RecipeService;
use App\Services\WeeklyMenuService;
use Carbon;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class WeeklyMenuController
 * @package App\Http\Controllers\Backend
 */
class WeeklyMenuController extends BackendController
{
    
    /**
     * @var string
     */
    public $module = "weekly_menu";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'weeklymenu.read',
        'create'  => 'weeklymenu.create',
        'store'   => 'weeklymenu.create',
        'show'    => 'weeklymenu.read',
        'edit'    => 'weeklymenu.read',
        'update'  => 'weeklymenu.write',
        'destroy' => 'weeklymenu.delete',
    ];
    
    /**
     * @var WeeklyMenu
     */
    public $model;
    
    /**
     * @var \App\Services\WeeklyMenuService
     */
    private $weeklyMenuService;
    
    /**
     * @var \App\Services\BasketService
     */
    private $basketService;
    
    /**
     * @var \App\Services\RecipeService
     */
    private $recipeService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\WeeklyMenuService               $weeklyMenuService
     * @param \App\Services\RecipeService                   $recipeService
     * @param \App\Services\BasketService                   $basketService
     */
    public function __construct(
        ResponseFactory $response,
        WeeklyMenuService $weeklyMenuService,
        RecipeService $recipeService,
        BasketService $basketService
    ) {
        parent::__construct($response);
        
        $this->weeklyMenuService = $weeklyMenuService;
        $this->recipeService = $recipeService;
        $this->basketService = $basketService;
        
        Meta::title(trans('labels.weekly_menus'));
        
        $this->breadcrumbs(trans('labels.weekly_menus'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /weekly_menu
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            return $this->weeklyMenuService->table();
        }
        
        $this->data('page_title', trans('labels.weekly_menus'));
        $this->breadcrumbs(trans('labels.weekly_menus_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Display menu of current week.
     *
     * @return \Response
     */
    public function current()
    {
        $model = WeeklyMenu::with('baskets', 'baskets.recipes', 'baskets.recipes.recipe.ingredients')
            ->current()
            ->first();
        
        if (!$model) {
            session()->put('current_week_menu', true);
            
            FlashMessages::add('error', trans('messages.current week menu is not created yet'));
            
            return redirect()->route('admin.'.$this->module.'.create');
        }
        
        $this->data('model', $model);
        
        $this->data('page_title', trans('labels.current_week_menu'));
        
        $this->breadcrumbs(trans('labels.current_week_menu'));
        
        $this->_fillAdditionalTemplateData($model);
        
        return $this->render('views.'.$this->module.'.edit');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /weekly_menu/create
     *
     * @return Response
     */
    public function create()
    {
        $model = new WeeklyMenu();
        
        if (session('current_week_menu', false)) {
            $model->week = Carbon::now()->weekOfYear;
            $model->year = Carbon::now()->year;
            
            session()->forget('current_week_menu');
        }
        
        $this->data('model', $model);
        
        $this->data('page_title', trans('labels.weekly_menu_creating'));
        
        $this->breadcrumbs(trans('labels.weekly_menu_creating'));
        
        $this->_fillAdditionalTemplateData($model);
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /weekly_menu
     *
     * @param WeeklyMenuCreateRequest $request
     *
     * @return \Response
     */
    public function store(WeeklyMenuCreateRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $model = new WeeklyMenu($request->all());
            
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.edit', $model->id);
        } catch (Exception $e) {
            DB::rollBack();
            
            FlashMessages::add('error', trans('messages.save_failed'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     * GET /weekly_menu/{id}
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
     * GET /weekly_menu/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = WeeklyMenu::with('baskets', 'baskets.recipes', 'baskets.recipes.recipe.ingredients')
                ->whereId($id)
                ->firstOrFail();
            
            if ($model->isCurrentWeekMenu()) {
                return redirect()->route('admin.'.$this->module.'.current');
            }
            
            $this->data(
                'page_title',
                trans('labels.weekly_menu').': '.trans(
                    'labels.w_label'
                ).$model->week.', '.$model->year.' ('.$model->getWeekDates().')'
            );
            
            $this->breadcrumbs(trans('labels.weekly_menu_editing'));
            
            $this->_fillAdditionalTemplateData($model);
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /weekly_menu/{id}
     *
     * @param  int                    $id
     * @param WeeklyMenuUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, WeeklyMenuUpdateRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $model = WeeklyMenu::findOrFail($id);
            
            $input = $request->all();
            
            if ($model->isCurrentWeekMenu()) {
                unset($input['week']);
                unset($input['year']);
            }
            
            $model->update($input);
            
            $this->_saveRelationships($model, $request);
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            if ($model->isCurrentWeekMenu()) {
                return redirect()->route('admin.'.$this->module.'.current');
            }
            
            return redirect()->route('admin.'.$this->module.'.edit', $model->id);
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
     * @return array
     */
    public function getBasketSelectPopup()
    {
        try {
            $baskets = Basket::basic()->get();
            
            return [
                'status' => 'success',
                'html'   => view('views.'.$this->module.'.popups.basket_select')
                    ->with('baskets', $baskets)
                    ->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function addBasket(Request $request)
    {
        try {
            $portions = $request->get('portions', config('weekly_menu.default_portions_count'));
            
            $basket = Basket::whereId($request->get('basket_id'))->firstOrFail();
            $recipes = $basket->allowed_recipes()->where('portions', $portions)->get();
            
            return [
                'status'       => 'success',
                'tab_html'     => view('views.'.$this->module.'.partials.basket_add_tab')
                    ->with(
                        [
                            'basket'   => $basket,
                            'portions' => $portions,
                        ]
                    )
                    ->render(),
                'content_html' => view('views.'.$this->module.'.partials.basket_add_content')
                    ->with(
                        [
                            'basket'   => $basket,
                            'portions' => $portions,
                            'recipes'  => $recipes,
                        ]
                    )
                    ->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int  $basket_id
     * @param int  $portions
     * @param int  $recipe_id
     * @param bool $copy
     *
     * @return array
     */
    public function getRecipeItem($basket_id, $portions, $recipe_id, $copy = false)
    {
        try {
            $model = $this->recipeService->getRecipeFormWeeklyMenu($recipe_id, $copy, $portions);
            
            $html = $model ?
                view('views.'.$this->module.'.partials.recipe_item')
                    ->with(['basket_id' => $basket_id, 'portions' => $portions, 'model' => $model])
                    ->render() :
                '';
            
            return [
                'status'    => 'success',
                'html'      => $html,
                'recipe_id' => $model ? $model->id : 0,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $basket_id
     * @param int $portions
     *
     * @return array
     */
    public function getBasketAvailableRecipes($basket_id, $portions)
    {
        try {
            $basket = Basket::findOrFail($basket_id);
            $recipes = $basket->allowed_recipes()->visible()->where('portions', $portions)->get();
            
            return [
                'status'  => 'success',
                'recipes' => $recipes,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $basket_id
     * @param int $portions
     *
     * @return array
     */
    public function getBasketCopyForm($basket_id, $portions)
    {
        try {
            $model = Basket::whereId($basket_id)->firstOrFail();
            
            return [
                'title'   => trans('labels.weekly_menu_basket_copy_form_title'),
                'message' => view('views.'.$this->module.'.popups.basket_copy_form')
                    ->with(['model' => $model, 'portions' => $portions])
                    ->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     *
     * fill additional template data
     *
     * @param WeeklyMenu|null $model
     */
    private function _fillAdditionalTemplateData($model)
    {
        $this->data('baskets', $model->baskets);
    }
    
    /**
     * @param \App\Models\WeeklyMenu   $model
     * @param \Illuminate\Http\Request $request
     */
    private function _saveRelationships(WeeklyMenu $model, Request $request)
    {
        $exists_baskets = [];
        
        foreach ($request->get('baskets', []) as $_basket) {
            $basket = $this->weeklyMenuService->saveBasket(
                $model,
                [
                    'basket_id' => $_basket['id'],
                    'portions'  => $_basket['portions'],
                ]
            );
            $this->weeklyMenuService->processRecipes($basket, $_basket);
            
            $exists_baskets[] = $basket->id;
        }
        
        $this->weeklyMenuService->removeDeletedBaskets($model, $exists_baskets);
    }
}