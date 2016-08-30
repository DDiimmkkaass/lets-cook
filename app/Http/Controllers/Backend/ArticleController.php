<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Controllers\Backend;

use App\Events\Backend\ArticleDelete;
use App\Http\Requests\Backend\Article\ArticleCreateRequest;
use App\Http\Requests\Backend\Article\ArticleUpdateRequest;
use App\Models\Article;
use App\Services\ArticleService;
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
 * Class ArticleController
 * @package App\Http\Controllers\Backend
 */
class ArticleController extends BackendController
{

    use AjaxFieldsChangerTrait;
    use ProcessTagsTrait;

    /**
     * @var string
     */
    public $module = "article";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'article.read',
        'create'          => 'article.create',
        'store'           => 'article.create',
        'show'            => 'article.read',
        'edit'            => 'article.read',
        'update'          => 'article.write',
        'destroy'         => 'article.delete',
        'ajaxFieldChange' => 'article.write',
    ];

    /**
     * @var ArticleService
     */
    private $articleService;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\ArticleService                     $articleService
     */
    public function __construct(ResponseFactory $response, ArticleService $articleService)
    {
        parent::__construct($response);

        $this->articleService = $articleService;

        Meta::title(trans('labels.articles'));

        $this->breadcrumbs(trans('labels.articles'), route('admin.'.$this->module.'.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }
    
    /**
     * Display a listing of the resource.
     * GET /article
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Article::withTranslations()->joinTranslations('articles', 'article_translations')->select(
                'articles.id',
                'article_translations.name',
                'status',
                'position',
                'slug'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'articles.id', '=', '$1')
                ->filterColumn('article_translations.name', 'where', 'article_translations.name', 'LIKE', '%$1%')
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

        $this->data('page_title', trans('labels.articles'));
        $this->breadcrumbs(trans('labels.articles_list'));

        return $this->render('views.'.$this->module.'.index');
    }
    
    
    /**
     * Show the form for creating a new resource.
     * GET /article/create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->data('model', new Article);

        $this->data('page_title', trans('labels.article_creating'));

        $this->breadcrumbs(trans('labels.article_creating'));

        $this->_fillAdditionTemplateData();

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /article
     *
     * @param ArticleCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ArticleCreateRequest $request)
    {
        DB::beginTransaction();

        try {
            $model = new Article($request->all());
            $model->save();

            $this->articleService->setExternalUrl($model);

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
     * GET /article/{id}
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
     * GET /article/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {
            $model = Article::with('translations', 'tags')->whereId($id)->firstOrFail();

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.article_editing'));

            $this->_fillAdditionTemplateData($model);

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /article/{id}
     *
     * @param  int              $id
     * @param ArticleUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, ArticleUpdateRequest $request)
    {
        try {
            $model = Article::findOrFail($id);

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
     * DELETE /article/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $model = Article::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                Event::fire(new ArticleDelete($id));

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
     * set to template addition variables for add\update article
     *
     * @param Article|null $model
     */
    private function _fillAdditionTemplateData($model = null)
    {
        $this->data('tags', $this->getTagsList());

        $this->data('selected_tags', $this->getSelectedTagsList($model));
    }
}