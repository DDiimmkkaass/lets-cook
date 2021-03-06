<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Controllers\Backend;

use App\Events\Backend\NewsDelete;
use App\Http\Requests\Backend\News\NewsCreateRequest;
use App\Http\Requests\Backend\News\NewsUpdateRequest;
use App\Models\News;
use App\Services\NewsService;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use App\Traits\Controllers\ProcessTagsTrait;
use Datatables;
use DB;
use Event;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;

/**
 * Class NewsController
 * @package App\Http\Controllers\Backend
 */
class NewsController extends BackendController
{
    
    use AjaxFieldsChangerTrait;
    use ProcessTagsTrait;
    
    /**
     * @var string
     */
    public $module = "news";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'news.read',
        'create'          => 'news.create',
        'store'           => 'news.create',
        'show'            => 'news.read',
        'edit'            => 'news.read',
        'update'          => 'news.write',
        'destroy'         => 'news.delete',
        'ajaxFieldChange' => 'news.write',
    ];
    
    /**
     * @var NewsService
     */
    private $newsService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\NewsService                     $newsService
     */
    public function __construct(ResponseFactory $response, NewsService $newsService)
    {
        parent::__construct($response);
        
        $this->newsService = $newsService;
        
        Meta::title(trans('labels.blog'));
        
        $this->breadcrumbs(trans('labels.blog'), route('admin.'.$this->module.'.index'));
        
        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }
    
    /**
     * Display a listing of the resource.
     * GET /news
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = News::withTranslations()->joinTranslations('news', 'news_translations')->select(
                'news.id',
                'news_translations.name',
                'status',
                'position',
                'slug'
            );
            
            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'news.id', '=', '$1')
                ->filterColumn('news_translations.name', 'where', 'news_translations.name', 'LIKE', '%$1%')
                ->editColumn(
                    'status',
                    function ($model) {
                        return view(
                            'partials.datatables.toggler',
                            ['model' => $model, 'type' => $this->module, 'field' => 'status']
                        )->render();
                    }
                )
                ->editColumn(
                    'position',
                    function ($model) {
                        return view(
                            'partials.datatables.text_input',
                            ['model' => $model, 'type' => $this->module, 'field' => 'position']
                        )->render();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'front_link' => true, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('short_content')
                ->removeColumn('content')
                ->removeColumn('meta_keywords')
                ->removeColumn('meta_title')
                ->removeColumn('meta_description')
                ->removeColumn('parent')
                ->removeColumn('translations')
                ->removeColumn('slug')
                ->make();
        }
        
        $this->_fillAdditionTemplateData();
        
        $this->data('page_title', trans('labels.blog'));
        $this->breadcrumbs(trans('labels.news_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    
    /**
     * Show the form for creating a new resource.
     * GET /news/create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->data('model', new News);
        
        $this->data('page_title', trans('labels.news_creating'));
        
        $this->breadcrumbs(trans('labels.news_creating'));
        
        $this->_fillAdditionTemplateData();
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /news
     *
     * @param NewsCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NewsCreateRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $model = new News($request->all());
            $model->save();
            
            $this->newsService->setExternalUrl($model);
            
            $this->processTags($model);
            
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
     * GET /news/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return $this->edit($id);
    }
    
    /**
     * Show the form for editing the specified resource.
     * GET /news/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {
            $model = News::with('translations', 'tags')->whereId($id)->firstOrFail();
            
            $this->data('page_title', '"'.$model->name.'"');
            
            $this->breadcrumbs(trans('labels.news_editing'));
            
            $this->_fillAdditionTemplateData($model);
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /news/{id}
     *
     * @param  int              $id
     * @param NewsUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, NewsUpdateRequest $request)
    {
        try {
            $model = News::findOrFail($id);
            
            DB::beginTransaction();
            
            $model->fill($request->all());
            $model->update();
            
            $this->processTags($model);
            
            DB::commit();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            DB::rollBack();
            
            FlashMessages::add("error", trans('messages.update_error'));
        }
        
        return redirect()->back()->withInput();
    }
    
    /**
     * Remove the specified resource from storage.
     * DELETE /news/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $model = News::findOrFail($id);
            
            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                Event::fire(new NewsDelete($id));
                
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error'));
        }
        
        return redirect()->route('admin.'.$this->module.'.index');
    }
    
    /**
     * set to template addition variables for add\update news
     *
     * @param News|null $model
     */
    private function _fillAdditionTemplateData($model = null)
    {
        $this->data('tags', $this->getTagsList());
        
        $this->data('selected_tags', $this->getSelectedTagsList($model));
    }
}