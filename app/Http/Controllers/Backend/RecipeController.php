<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Recipe\RecipeCopyRequest;
use App\Http\Requests\Backend\Recipe\RecipeRequest;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Services\RecipeService;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use App\Traits\Controllers\ProcessMediaTrait;
use App\Traits\Controllers\ProcessTagsTrait;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Response;

/**
 * Class RecipeController
 * @package App\Http\Controllers\Backend
 */
class RecipeController extends BackendController
{
    
    use AjaxFieldsChangerTrait;
    use ProcessMediaTrait;
    use ProcessTagsTrait;
    
    /**
     * @var string
     */
    public $module = "recipe";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'recipe.read',
        'create'  => 'recipe.create',
        'store'   => 'recipe.create',
        'show'    => 'recipe.read',
        'edit'    => 'recipe.read',
        'update'  => 'recipe.write',
        'destroy' => 'recipe.delete',
    ];
    
    /**
     * @var \App\Services\RecipeService
     */
    private $recipeService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\RecipeService                   $recipeService
     */
    public function __construct(ResponseFactory $response, RecipeService $recipeService)
    {
        parent::__construct($response);
        
        $this->recipeService = $recipeService;
        
        Meta::title(trans('labels.recipes'));
        
        $this->breadcrumbs(trans('labels.recipes'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /recipe
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            return $this->recipeService->table($request);
        }
        
        $this->data('page_title', trans('labels.recipes'));
        $this->breadcrumbs(trans('labels.recipes_list'));
        
        $this->_fillIndexAdditionalTemplateData();
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /recipe/create
     *
     * @return \Response
     */
    public function create()
    {
        $model = new Recipe();
        
        $this->data('model', $model);
        
        $this->data('page_title', trans('labels.recipe_creating'));
        
        $this->breadcrumbs(trans('labels.recipe_creating'));
        
        $this->_fillAdditionalTemplateData();
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /recipe
     *
     * @param RecipeRequest $request
     *
     * @return \Response
     */
    public function store(RecipeRequest $request)
    {
        try {
            $bind = $request->get('bind', false);
            $parent_id = $request->get('parent_id', 0);
            
            if ($bind && $parent_id) {
                $parent_model = Recipe::findOrFail($parent_id);
    
                $model = Recipe::whereBindId($parent_model->bind_id)
                    ->wherePortions($request->get('portions'))
                    ->where('id', '<>', $parent_model->id)
                    ->first();
                
                if ($model) {
                    FlashMessages::add(
                        'error',
                        trans('messages.this recipe with the same number of portions is already exists')
                    );

                    return redirect()->back()->withInput();
                }
            }
            
            DB::beginTransaction();
            
            $model = new Recipe($request->all());
            $model->save();
            
            $this->_saveRelationships($model, $request);
            
            if ($bind) {
                $this->recipeService->bindRecipes($parent_model, $model);
            }
            
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
     * GET /recipe/{id}
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
     * GET /recipe/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Recipe::with('ingredients', 'home_ingredients', 'baskets', 'steps', 'orders', 'files')
                ->whereId($id)
                ->firstOrFail();
            
            $page_title = '"'.$model->name.'" '.($model->draft ? view('recipe.partials.draft_label')->render() : '');
            
            $this->data('page_title', $page_title);
            
            $this->breadcrumbs(trans('labels.recipe_editing'));
            
            $this->_fillAdditionalTemplateData($model);
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /recipe/{id}
     *
     * @param  int          $id
     * @param RecipeRequest $request
     *
     * @return \Response
     */
    public function update($id, RecipeRequest $request)
    {
        try {
            $model = Recipe::whereId($id)->firstOrFail();
    
            if ($model->bind_id) {
                $_model = Recipe::whereBindId($model->bind_id)
                    ->wherePortions($request->get('portions'))
                    ->where('id', '<>', $model->id)
                    ->first();
                
                if ($_model) {
                    FlashMessages::add(
                        'error',
                        trans('messages.this recipe with the same number of portions is already exists')
                    );
    
                    return redirect()->back()->withInput();
                }
            }
            
            DB::beginTransaction();
            
            $model->fill($request->all());
            $model->draft = $request->get('draft', 0);
            
            $model->save();
            
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
     * @param int                                                 $recipe_id
     * @param \App\Http\Requests\Backend\Recipe\RecipeCopyRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Response
     */
    public function copy($recipe_id, RecipeCopyRequest $request)
    {
        try {
            $portions = $request->get('portions');
            
            $model = Recipe::with('ingredients', 'home_ingredients', 'baskets', 'steps', 'files')
                ->whereId($recipe_id)
                ->firstOrFail();
            
            if ($request->get('bind', false) && $model->portions == $portions) {
                FlashMessages::add(
                    'error',
                    trans('messages.this recipe with the same number of portions is already exists')
                );
                
                return redirect()->route('admin.'.$this->module.'.index');
            }
    
            $this->data('multiplier', $portions / $model->portions);
            
            $model->portions = $portions;
            
            $this->data('model', $model);
            
            $this->data('bind', $request->get('bind', false));
            
            $this->data(
                'page_title',
                trans('labels.copy_recipe').' "'.$model->name.'" '.
                ($model->draft ? view('recipe.partials.draft_label')->render() : '')
            );
            
            $this->breadcrumbs(trans('labels.copy_recipe'));
            
            $this->_fillAdditionalTemplateData($model);
            
            return $this->render('views.'.$this->module.'.copy');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.copy error, parent recipe not found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * @param int $recipe_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeleteForm($recipe_id)
    {
        $menus = $this->recipeService->getWeeklyMenusWhereUsedRecipe($recipe_id);
        
        return response()->json(
            [
                'title'   => trans('labels.deleting_record'),
                'message' => view('views.'.$this->module.'.partials.delete_message', ['menus' => $menus])
                    ->render(),
            ]
        );
    }
    
    /**
     * Remove the specified resource from storage.
     * DELETE /recipe/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $menus = $this->recipeService->getWeeklyMenusWhereUsedRecipe($id);
            
            if (count($menus)) {
                FlashMessages::add(
                    'error',
                    trans('messages.you can not delete this recipe because it is still used menu')
                );
                
                return redirect()->route('admin.'.$this->module.'.index');
            }
            
            $model = Recipe::findOrFail($id);
            
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
     * @param int    $ingredient_id
     * @param string $type
     *
     * @return array
     */
    public function getIngredientRow($ingredient_id, $type = 'normal')
    {
        try {
            $model = Ingredient::with('unit')->findOrFail($ingredient_id);
            
            $key = $type != 'normal' ? 'ingredients_'.$type : 'ingredients';
            
            return [
                'status' => 'success',
                'html'   => view('views.'.$this->module.'.partials.ingredient_row', compact('model', 'type', 'key'))
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
     * @param int $recipe_id
     *
     * @return array
     */
    public function getCopyForm($recipe_id)
    {
        try {
            $model = Recipe::whereId($recipe_id)->firstOrFail();
            
            return [
                'title'   => trans('labels.recipe_copy_form_title'),
                'message' => view('views.'.$this->module.'.popups.copy_form')->with(['model' => $model])->render(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     *  fill additional template data for index route
     */
    private function _fillIndexAdditionalTemplateData()
    {
        $baskets = ['' => trans('labels.please_select')];
        foreach (Basket::positionSorted()->get(['id', 'name']) as $item) {
            $baskets[$item->id] = $item->name;
        }
        $this->data('baskets', $baskets);
        
        $portions = ['' => trans('labels.please_select')];
        foreach (config('recipe.available_portions') as $portion) {
            $portions[$portion] = $portion.' '.trans_choice('labels.count_of_portions', $portion);
        }
        $this->data('portions', $portions);
        
        $this->data(
            'statuses',
            ['' => trans('labels.please_select'), '1' => trans('labels.status_on'), '0' => trans('labels.status_off')]
        );
    
        $this->data('tags', $this->getTagsList());
    }
    
    /**
     *  fill additional template data
     *
     * @param \App\Models\Recipe|null $model
     */
    private function _fillAdditionalTemplateData($model = null)
    {
        $ingredient_categories = ['' => trans('labels.please_select_ingredient_category')];
        foreach (Category::positionSorted()->get(['id', 'name']) as $item) {
            $ingredient_categories[$item->id] = $item->name;
        }
        $this->data('ingredient_categories', $ingredient_categories);
        
        $this->data('ingredients', ['' => trans('labels.please_select_ingredient_category')]);
        
        $portions = [];
        foreach (config('recipe.available_portions') as $portion) {
            $portions[$portion] = $portion.' '.trans_choice('labels.count_of_portions', $portion);
        }
        $this->data('portions', $portions);
        
        $baskets = [];
        foreach (Basket::positionSorted()->get(['id', 'name']) as $item) {
            $baskets[$item->id] = $item->name;
        }
        $this->data('baskets', $baskets);
        
        $selected_baskets = [];
        
        if ($model) {
            $selected_baskets = [];
            foreach ($model->baskets as $item) {
                $selected_baskets[] = $item->id;
            }
            
            $this->data('orders', $this->recipeService->getStatisticOfOrder($model));
        }
        
        $this->data('selected_baskets', $selected_baskets);
    
        $this->data('tags', $this->getTagsList());
    
        $this->data('selected_tags', $this->getSelectedTagsList($model));
    }
    
    /**
     * @param \App\Models\Recipe       $model
     * @param \Illuminate\Http\Request $request
     */
    private function _saveRelationships(Recipe $model, Request $request)
    {
        $model->baskets()->sync($request->get('baskets', []));
        
        $this->recipeService->processIngredients($model, $request->get('ingredients', []));
        
        $this->recipeService->processIngredients($model, $request->get('ingredients_home', []));
        
        $this->recipeService->processSteps(
            $model,
            $request->get('steps', [])
        );
        
        $this->processMedia($model, 'files');
        
        $this->processTags($model);
    }
}