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
use App\Services\TagService;
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
     * @var \App\Services\TagService
     */
    private $tagService;
    
    /**
     * RecipeController constructor.
     *
     * @param \App\Services\RecipeService $recipeService
     * @param \App\Services\TagService    $tagService
     */
    public function __construct(RecipeService $recipeService, TagService $tagService)
    {
        parent::__construct();

        $this->recipeService = $recipeService;
        $this->tagService = $tagService;
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
        $model = Recipe::with(['ingredients', 'home_ingredients', 'steps', 'media'])
            ->visible()
            ->whereId($recipe_id)
            ->first();
        
        abort_if(!$model, 404);
    
        $active_baskets = $this->recipeService->activeBaskets($model);
        $tags = $this->tagService->tagsForItem($model);
        
        $this->data('model', $model);
        $this->data('tags', $tags);
        $this->data('active_baskets', $active_baskets);
        
        $this->fillMeta($model, $this->module);
        
        return $this->render($this->module.'.show');
    }
}