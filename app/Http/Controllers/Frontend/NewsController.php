<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Models\News;
use App\Services\NewsService;

/**
 * Class NewsController
 * @package App\Http\Controllers\Frontend
 */
class NewsController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'news';

    /**
     * @var \App\Services\NewsService
     */
    protected $newsService;

    /**
     * NewsController constructor.
     *
     * @param \App\Services\NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        parent::__construct();

        $this->newsService = $newsService;
    }

    /**
     * @return $this|\App\Http\Controllers\Frontend\NewsController
     */
    public function index()
    {
        $this->data('list', $this->newsService->getList());

        return $this->render($this->module.'.index');
    }

    /**
     * @param string $slug
     *
     * @return $this|\App\Http\Controllers\Frontend\NewsController
     */
    public function show($slug = '')
    {
        $model = News::with(['translations', 'tags', 'tags.tag.translations'])->visible()->whereSlug($slug)->first();

        abort_if(!$model, 404);

        $this->newsService->getRelatedNewsForNews($model);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        return $this->render($this->module.'.show');
    }
}