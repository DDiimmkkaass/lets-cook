<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Recipe;
use App\Services\RecipeService;
use Illuminate\Http\Request;
use Meta;

/**
 * Class RecipeController
 * @package App\Http\Controllers\Frontend
 */
class RecipeController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'recipe';

    /**
     * @var \App\Services\RecipeService
     */
    protected $recipeService;

    /**
     * RecipeController constructor.
     *
     * @param \App\Services\RecipeService $recipeService
     */
    public function __construct(RecipeService $recipeService)
    {
        parent::__construct();

        $this->recipeService = $recipeService;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\View\View|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $list = $this->recipeService->getList();
        
        if ($request->ajax()) {
            return $list;
        }
        
        $this->data('list', $list['recipes']);
        $this->data('next_count', $list['next_count']);
    
        Meta::canonical(localize_route('recipes.index'));
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @param int $recipe_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($recipe_id)
    {
        $model = Recipe::with(['tags', 'tags.tag.translations', 'ingredients', 'home_ingredients', 'steps', 'media'])
            ->visible()
            ->whereId($recipe_id)
            ->first();
        
        abort_if(!$model, 404);
    
        $active_baskets = $this->recipeService->activeBaskets($model);
        
        $this->data('model', $model);
        $this->data('active_baskets', $active_baskets);
        
        $this->fillMeta($model, $this->module);
        
        return $this->render($this->module.'.show');
    }
}