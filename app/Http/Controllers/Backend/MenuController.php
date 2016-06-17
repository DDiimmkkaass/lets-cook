<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.06.15
 * Time: 17:50
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Menu\MenuRequest;
use App\Models\Menu;
use App\Models\MenuItem;
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
 * Class MenuController
 * @package App\Http\Controllers\Backend
 */
class MenuController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "menu";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'menu.read',
        'show'            => 'menu.read',
        'create'          => 'menu.write',
        'store'           => 'menu.write',
        'edit'            => 'menu.write',
        'update'          => 'menu.write',
        'destroy'         => 'menu.delete',
        'ajaxFieldChange' => 'menu.write',
    ];

    /**
     * @param \Illuminate\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        $this->breadcrumbs(trans('labels.menus'), route('admin.menu.index'));

        Meta::title(trans('labels.menu'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /menu
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Menu::withTranslations()->joinTranslations('menus', 'menu_translations')->select(
                'menus.id',
                'menu_translations.name',
                'menus.layout_position',
                'menus.status',
                'menus.position'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('menus.id', 'where', 'menus.id', '=', '$1')
                ->filterColumn('layout_position', 'where', 'menus.layout_position', 'LIKE', '%$1%')
                ->filterColumn('name', 'where', 'menu_translations.name', 'LIKE', '%$1%')
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
                            ['model' => $model, 'type' => 'menu']
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('translations')
                ->make();
        }

        $this->data('page_title', trans('labels.menus'));
        $this->breadcrumbs(trans('labels.menus_list'));

        return $this->render('views.menu.index');
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
        $this->data('model', new Menu());

        $this->data('page_title', trans('labels.menu_create'));

        $this->breadcrumbs(trans('labels.menu_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('menu.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /menu
     *
     * @param \App\Http\Requests\Backend\Menu\MenuRequest $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(MenuRequest $request)
    {
        DB::beginTransaction();

        try {
            $input = $request->all();

            $model = new Menu($input);
            $model->save();

            $this->_processItems($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.menu.index');
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
            $model = Menu::with('items')->findOrFail($id);

            $this->_fillAdditionalTemplateData();

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.menu_editing'));

            return $this->render('views.menu.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.menu.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                        $id
     * @param \App\Http\Requests\Backend\Menu\MenuRequest $request
     *
     * @return \Response
     */
    public function update($id, MenuRequest $request)
    {
        try {
            $model = Menu::findOrFail($id);

            DB::beginTransaction();

            $model->fill($request->all());
            $model->save();

            $this->_processItems($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.menu.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.menu.index');
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
            $model = Menu::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.menu.index');
    }

    /**
     * fill additional template data
     */
    private function _fillAdditionalTemplateData()
    {
        $this->data(
            'templates',
            get_templates(base_path('resources/themes/'.config('app.theme').'/widgets/menu/templates'))
        );
    }

    /**
     * @param \App\Models\Menu $model
     */
    private function _processItems(Menu $model)
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
                $_item = MenuItem::findOrFail($key);
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
                $item = new MenuItem($item);
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