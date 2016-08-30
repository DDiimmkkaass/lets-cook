<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Article;
use App\Services\ArticleService;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Frontend
 */
class ArticleController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'article';

    /**
     * @var \App\Services\ArticleService
     */
    protected $articleService;

    /**
     * ArticleController constructor.
     *
     * @param \App\Services\ArticleService $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        parent::__construct();

        $this->articleService = $articleService;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->data('list', $this->articleService->getList());

        return $this->render($this->module.'.index');
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug = '')
    {
        $model = Article::with(['translations', 'tags', 'tags.tag.translations'])->visible()->whereSlug($slug)->first();
        
        abort_if(!$model, 404);

        $this->articleService->getRelatedArticlesForArticle($model);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        return $this->render($this->module.'.show');
    }
}