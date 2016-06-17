<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Banner\BannerRequest;
use App\Models\Banner;
use App\Models\BannerItem;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Meta;
use Redirect;
use Response;

/**
 * Class BannerController
 * @package App\Http\Controllers\Backend
 */
class BannerController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "banner";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'banner.read',
        'create'          => 'banner.create',
        'store'           => 'banner.create',
        'show'            => 'banner.read',
        'edit'            => 'banner.read',
        'update'          => 'banner.write',
        'destroy'         => 'banner.delete',
        'ajaxFieldChange' => 'banner.write',
    ];

    /**
     * @var
     */
    protected $form;

    /**
     * @param \Illuminate\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        $this->breadcrumbs(trans('labels.banners'), route('admin.banner.index'));

        Meta::title(trans('labels.banners'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /banner
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Banner::joinTranslations('banners')->select(
                'banners.id',
                'banner_translations.title',
                'banners.layout_position',
                'banners.status',
                'banners.position'
            );

            return $dataTables = Datatables::of($list)
                ->filter_column('banners.id', 'where', 'banners.id', '=', '$1')
                ->filter_column('banners.layout_position', 'where', 'banners.layout_position', 'LIKE', '%$1%')
                ->filter_column('banner_translations.title', 'where', 'banner_translations.title', 'LIKE', '%$1%')
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
                            ['model' => $model, 'type' => $this->module]
                        )->render();
                    }
                )
                ->set_index_column('id')
                ->make();
        }

        $this->data('page_title', trans('labels.banners'));
        $this->breadcrumbs(trans('labels.banners_list'));

        return $this->render('views.banner.index');
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * @return $this
     */
    public function create()
    {
        $this->data('model', new Banner());

        $this->data('page_title', trans('labels.banner_creating'));

        $this->breadcrumbs(trans('labels.banner_creating'));

        $this->_fillAdditionalTemplateData();

        return $this->render('banner.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /banner
     *
     * @param \App\Http\Requests\Backend\Banner\BannerRequest $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(BannerRequest $request)
    {
        DB::beginTransaction();

        try {
            $input = $request->all();

            $model = new Banner($input);
            $model->save();

            $this->_processItems($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.banner.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Banner::with('items')->findOrFail($id);

            $this->_fillAdditionalTemplateData();

            $this->data('page_title', '"'.$model->title.'"');

            $this->breadcrumbs(trans('labels.banner_editing'));

            return $this->render('views.banner.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.banner.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                            $id
     * @param \App\Http\Requests\Backend\Banner\BannerRequest $request
     *
     * @return \Response
     */
    public function update($id, BannerRequest $request)
    {
        try {
            $model = Banner::findOrFail($id);

            DB::beginTransaction();

            $model->fill($request->all());
            $model->save();

            $this->_processItems($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.banner.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.banner.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Banner::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.banner.index');
    }

    /**
     * fill additional template data
     */
    private function _fillAdditionalTemplateData()
    {
        $this->data(
            'templates',
            get_templates(base_path('resources/themes/'.config('app.theme').'/widgets/banner/templates'))
        );
    }

    /**
     * @param \App\Models\Banner $model
     */
    private function _processItems(Banner $model)
    {
        $data = request('items.remove', []);
        foreach ($data as $id) {
            try {
                $item = $model->items()->findOrFail($id);
                $item->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.item destroy failure"." ".$id.". ".$e->getMessage()));
                continue;
            }
        }

        $data = request('items.old', []);
        foreach ($data as $key => $item) {
            try {
                $_item = BannerItem::findOrFail($key);
                $_item->update($item);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.item update failure"." ".$item['name'].". ".$e->getMessage())
                );
                continue;
            }
        }

        $data = request('items.new', []);
        foreach ($data as $item) {
            try {
                $item = new BannerItem($item);
                $model->items()->save($item);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.item save failure"." ".$item['name'].". ".$e->getMessage())
                );
                continue;
            }
        }
    }
}