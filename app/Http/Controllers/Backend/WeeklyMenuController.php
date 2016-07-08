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
use App\Models\Recipe;
use App\Models\WeeklyMenu;
use App\Services\BasketService;
use App\Services\WeeklyMenuService;
use Carbon;
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
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\WeeklyMenuService               $weeklyMenuService
     * @param \App\Services\BasketService                   $basketService
     */
    public function __construct(
        ResponseFactory $response,
        WeeklyMenuService $weeklyMenuService,
        BasketService $basketService
    ) {
        parent::__construct($response);

        $this->weeklyMenuService = $weeklyMenuService;
        $this->basketService = $basketService;

        $this->middleware('prepare.weekly_dates', ['only' => ['store', 'update']]);

        Meta::title(trans('labels.weekly_menus'));
        
        $this->breadcrumbs(trans('labels.weekly_menus'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /weekly_menu
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = WeeklyMenu::select(
                'id',
                'started_at',
                'ended_at'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'weekly_menu.id', '=', '$1')
                ->filterColumn('started_at', 'where', 'weekly_menu.started_at', 'LIKE', '%$1%')
                ->filterColumn('ended_at', 'where', 'weekly_menu.ended_at', 'LIKE', '%$1%')
                ->editColumn(
                    'started_at',
                    function ($model) {
                        return $model->getStartedAt() .
                        ($model->isCurrentWeekMenu() ? view('views.weekly_menu.partials.current_week_menu_label')->render() : '');
                    }
                )
                ->editColumn(
                    'ended_at',
                    function ($model) {
                        return $model->getEndedAt();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => $this->module, 'without_delete' => true]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->make();
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
        $model = WeeklyMenu::current()->first();

        if (!$model) {
            session()->put('current_week_menu', true);

            FlashMessages::add('error', trans('messages.current week menu is not created yet'));

            return redirect()->route('admin.'.$this->module.'.create');
        }

        $this->data('model', $model);

        $this->data('page_title', trans('labels.current_week_menu'));

        $this->breadcrumbs(trans('labels.current_week_menu'));

        $this->_fillAdditionalTemplateData();

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
            $model->started_at = Carbon::now()->startOfWeek();
            $model->ended_at = Carbon::now()->endOfWeek();

            session()->forget('current_week_menu');
        }

        $this->data('model', $model);
        
        $this->data('page_title', trans('labels.weekly_menu_creating'));
        
        $this->breadcrumbs(trans('labels.weekly_menu_creating'));

        $this->_fillAdditionalTemplateData();

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
            $model = WeeklyMenu::whereId($id)->firstOrFail();

            if ($model->isCurrentWeekMenu()) {
                return redirect()->route('admin.'.$this->module.'.current');
            }

            $this->data('page_title', '"'.$model->getWeekDates().'"');
            
            $this->breadcrumbs(trans('labels.weekly_menu_editing'));

            $this->_fillAdditionalTemplateData();
            
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
            
            $model->update($request->all());

            $this->_saveRelationships($model, $request);

            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
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
     * @param int $basket_id
     * @param int $recipe_id
     *
     * @return array
     */
    public function getRecipeItem($basket_id, $recipe_id)
    {
        try {
            $model = Recipe::visible()->findOrFail($recipe_id);

            return [
                'status' => 'success',
                'html'   => view('views.'.$this->module.'.partials.recipe_item')
                    ->with(['basket_id' => $basket_id, 'model' => $model])
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
     * fill additional template data
     */
    private function _fillAdditionalTemplateData()
    {
        $this->data('baskets', Basket::with('recipes', 'allowed_recipes')->basic()->get());
    }
    
    /**
     * @param \App\Models\WeeklyMenu   $model
     * @param \Illuminate\Http\Request $request
     */
    private function _saveRelationships(WeeklyMenu $model, Request $request)
    {
        foreach ($request->get('baskets', []) as $basket_id => $recipes) {
            $basket = Basket::whereId($basket_id)->first();

            $this->basketService->processRecipes($basket, (array) $recipes, $model->id);
        }
    }
}