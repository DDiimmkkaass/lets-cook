<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\TextWidget\TextWidgetRequest;
use App\Models\TextWidget;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;

/**
 * Class TextWidgetController
 * @package App\Http\Controllers\Backend
 */
class TextWidgetController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "text_widget";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'textwidget.read',
        'create'          => 'textwidget.create',
        'store'           => 'textwidget.create',
        'show'            => 'textwidget.read',
        'edit'            => 'textwidget.read',
        'update'          => 'textwidget.write',
        'destroy'         => 'textwidget.delete',
        'ajaxFieldChange' => 'textwidget.write',
    ];

    /**
     * @var TextWidget
     */
    public $model;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.text_widgets'));

        $this->breadcrumbs(trans('labels.text_widgets'), route('admin.text_widget.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /text_widget
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = TextWidget::withTranslations()
                ->joinTranslations('text_widgets', 'text_widget_translations', 'id', 'text_widget_id')
                ->select(
                    'text_widgets.id',
                    'text_widget_translations.title',
                    'text_widgets.layout_position',
                    'text_widgets.status',
                    'text_widgets.position',
                    DB::raw('1 as actions')
                );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'text_widgets.id', '=', '$1')
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
                ->setIndexColumn('id')
                ->removeColumn('translations')
                ->make();
        }

        $this->data('page_title', trans('labels.text_widgets'));
        $this->breadcrumbs(trans('labels.text_widgets_list'));

        return $this->render('views.text_widget.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /text_widget/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new TextWidget);

        $this->data('page_title', trans('labels.text_widget_creating'));

        $this->breadcrumbs(trans('labels.text_widget_creating'));

        return $this->render('views.text_widget.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /text_widget
     *
     * @param TextWidgetRequest $request
     *
     * @return \Response
     */
    public function store(TextWidgetRequest $request)
    {
        $input = $request->all();

        try {
            $model = new TextWidget();
            $model->fill($input);

            $model->save();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.text_widget.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /text_widget/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /text_widget/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = TextWidget::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.text_widget.index');
        }

        $this->data('page_title', '"'.$model->title.'"');

        $this->breadcrumbs(trans('labels.text_widget_editing'));

        return $this->render('views.text_widget.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /text_widget/{id}
     *
     * @param  int              $id
     * @param TextWidgetRequest $request
     *
     * @return \Response
     */
    public function update($id, TextWidgetRequest $request)
    {
        try {
            $model = TextWidget::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.text_widget.index');
        }

        $input = $request->all();

        try {
            $model->update($input);

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.text_widget.index');
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /text_widget/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = TextWidget::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.text_widget.index');
    }
}