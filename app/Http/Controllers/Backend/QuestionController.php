<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Question\QuestionRequest;
use App\Models\Question;
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
 * Class QuestionController
 * @package App\Http\Controllers\Backend
 */
class QuestionController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "question";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'question.read',
        'create'          => 'question.create',
        'store'           => 'question.create',
        'show'            => 'question.read',
        'edit'            => 'question.read',
        'update'          => 'question.write',
        'destroy'         => 'question.delete',
        'ajaxFieldChange' => 'question.write',
    ];
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.all_questions'));

        $this->breadcrumbs(trans('labels.all_questions'), route('admin.'.$this->module.'.index'));
    }

    /**
     * Display a listing of the resource.
     * GET /question
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Question::withTranslations()
                ->joinTranslations('questions', 'question_translations', 'id', 'question_id')
                ->select(
                    'questions.id',
                    'question_translations.question',
                    'question_translations.answer',
                    'status',
                    'position'
                );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'questions.id', '=', '$1')
                ->filterColumn('question', 'where', 'question_translations.question', 'LIKE', '%$1%')
                ->filterColumn('answer', 'where', 'question_translations.answer', 'LIKE', '%$1%')
                ->editColumn(
                    'question',
                    function ($model) {
                        return str_limit($model->question);
                    }
                )
                ->editColumn(
                    'answer',
                    function ($model) {
                        return str_limit(strip_tags($model->answer));
                    }
                )
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

        $this->data('page_title', trans('labels.all_questions'));
        $this->breadcrumbs(trans('labels.questions_list'));

        return $this->render('views.'.$this->module.'.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /question/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Question);

        $this->data('page_title', trans('labels.question_create'));

        $this->breadcrumbs(trans('labels.question_create'));

        return $this->render('views.'.$this->module.'.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /question
     *
     * @param QuestionRequest $request
     *
     * @return \Response
     */
    public function store(QuestionRequest $request)
    {
        try {
            $model = new Question($request->all());

            $model->save();

            FlashMessages::add('success', trans('messages.save_ok'));

            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.save_failed'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /question/{id}
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
     * GET /question/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Question::findOrFail($id);

            $this->data('page_title', trans('labels.question_editing'));

            $this->breadcrumbs(trans('labels.question_editing'));

            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /question/{id}
     *
     * @param  int            $id
     * @param QuestionRequest $request
     *
     * @return \Response
     */
    public function update($id, QuestionRequest $request)
    {
        try {
            $model = Question::findOrFail($id);

            $model->fill($request->all());

            $model->save();

            FlashMessages::add('success', trans('messages.save_ok'));

            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.update_error'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /question/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Question::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error'));
        }

        return redirect()->route('admin.'.$this->module.'.index');
    }
}