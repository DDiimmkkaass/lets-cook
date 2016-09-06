<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 12:51
 */

namespace App\Http\ViewComposers;

use App\Models\Recipe;
use App\Services\TagService;
use Illuminate\View\View;

/**
 * Class RecipeComposer
 * @package App\Http\ViewComposers
 */
class RecipeComposer
{
    /**
     * @var \App\Services\TagService
     */
    private $tagService;
    
    /**
     * RecipeComposer constructor.
     *
     * @param \App\Services\TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }
    
    /**
     * Bind data to the view.
     *
     * @param  View $view
     */
    public function compose(View $view)
    {
        $view->with('tags_categories', $this->tagService->tagCategoriesByClass(Recipe::class));
    }
}