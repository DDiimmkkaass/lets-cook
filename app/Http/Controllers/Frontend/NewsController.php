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
use Illuminate\Http\Request;
use Meta;

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
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\View\View|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $list = $this->newsService->getList();
        
        if ($request->ajax()) {
            return $list;
        }
    
        $this->data('list', $list['blog']);
        $this->data('next_count', $list['next_count']);
    
        Meta::canonical(localize_route('blog.index'));
        
        return $this->render($this->module.'.index');
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug = '')
    {
        $model = News::with(['translations', 'tags', 'tags.tag.translations'])->visible()->whereSlug($slug)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        return $this->render($this->module.'.show');
    }
}