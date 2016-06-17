<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Services\PageService;
use Response;
use View;

/**
 * Class PageController
 * @package App\Http\Controllers\Frontend
 */
class PageController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'page';

    /**
     * @var \App\Services\PageService
     */
    protected $pageService;

    /**
     * PageController constructor.
     *
     * @param \App\Services\PageService $pageService
     */
    public function __construct(PageService $pageService)
    {
        parent::__construct();

        $this->pageService = $pageService;
    }

    /**
     * @return $this
     */
    public function getHome()
    {
        $model = Page::withTranslations()->whereSlug('home')->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        return $this->render('home');
    }

    /**
     * @return $this|\App\Http\Controllers\Frontend\PageController
     */
    public function getPage()
    {
        $slug = func_get_args();
        $slug = array_pop($slug);

        if ($slug == 'home') {
            return redirect(route('home'), 301);
        }

        $model = Page::with(['translations', 'parent', 'parent.translations'])->visible()->whereSlug($slug)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        return $this->render($this->pageService->getPageTemplate($model));
    }

    /**
     * @return $this
     */
    public function notFound()
    {
        $view = View::make('errors.404')->render();

        return Response::make($view, 404);
    }
}