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
use JavaScript;
use Meta;
use Response;

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
     * @return \Illuminate\Contracts\View\View
     */
    public function getHome()
    {
        abort_if(!$this->home_page, 404);
        
        $this->data('model', $this->home_page);
        
        return $this->render($this->module.'.templates.'.$this->home_page->template);
    }
    
    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getPage()
    {
        $slug = func_get_args();
        $slug = array_pop($slug);
        
        if ($slug == 'home') {
            return redirect(localize_route('home'), 301);
        }
        
        $model = Page::with(['translations', 'parent', 'parent.translations'])->visible()->whereSlug($slug)->first();
        
        abort_if(!$model, 404);
        
        $this->data('model', $model);
        
        $this->fillMeta($model, $this->module);
        
        return $this->render($this->module.'.templates.'.$model->template);
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function contacts()
    {
        JavaScript::put(['map_lat' => variable('map_marker_latitude'), 'map_lng' => variable('map_marker_longitude')]);
    
        Meta::canonical(localize_route('contacts'));
        
        return $this->render('page.contacts');
    }
    
    /**
     * @return \Illuminate\Http\Response
     */
    public function notFound()
    {
        $view = view('errors.404')->render();
        
        return Response::make($view, 404);
    }
    
    /**
     * @return \Illuminate\Http\Response
     */
    public function error()
    {
        $view = view('errors.500')->render();
        
        return Response::make($view, 500);
    }
}