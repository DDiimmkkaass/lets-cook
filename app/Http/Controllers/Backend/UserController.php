<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\User\PasswordChangeRequest;
use App\Http\Requests\Backend\User\UserCreateRequest;
use App\Http\Requests\Backend\User\UserUpdateRequest;
use App\Models\Field;
use App\Models\User;
use App\Models\UserInfo;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use App\Traits\Controllers\ProcessFieldsTrait;
use App\Traits\Controllers\SaveImageTrait;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;
use Sentry;

/**
 * Class UserController
 * @package App\Http\Controllers\Backend
 */
class UserController extends BackendController
{

    use ProcessFieldsTrait;
    use SaveImageTrait;
    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "user";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'user.read',
        'create'          => 'user.create',
        'store'           => 'user.create',
        'show'            => 'user.read',
        'edit'            => 'user.read',
        'update'          => 'user.write',
        'destroy'         => 'user.delete',
        'getNewPassword'  => 'user.write',
        'postNewPassword' => 'user.write',
        'ajaxFieldChange' => 'user.write',
    ];

    /**
     * @var
     */
    protected $userInfoForm;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.users'));

        $this->breadcrumbs(trans('labels.users'), route('admin.user.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = User::with('info')->joinInfo()->select(
                [
                    'users.id',
                    'user_info.name',
                    'email',
                    'user_info.phone',
                    'activated',
                ]
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('users.id', 'where', 'users.id', 'LIKE', '$1')
                ->filterColumn('user_info.name', 'where', 'user_info.name', 'LIKE', '%$1%')
                ->filterColumn('email', 'where', 'email', 'LIKE', '%$1%')
                ->filterColumn('user_info.phone', 'where', 'user_info.phone', 'LIKE', '%$1%')
                ->editColumn(
                    'activated',
                    function ($model) {
                        $model->status = $model->activated;

                        return view(
                            'partials.datatables.toggler',
                            ['model' => $model, 'type' => $this->module, 'field' => 'activated']
                        )->render();
                    }
                )
                ->addColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => 'user']
                        )->render();
                    }
                )
                ->setIndexColumn('users.id')
                ->removeColumn('info')
                ->make();
        }

        $this->data('page_title', trans('labels.users'));
        $this->breadcrumbs(trans('labels.users_list'));

        return $this->render('views.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $model = new User();
        $this->data('model', $model);

        $this->fillAdditionalTemplateData(__FUNCTION__);

        $this->data('page_title', trans('labels.user_create'));

        $this->breadcrumbs(trans('labels.user_create'));

        return $this->render('views.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Backend\User\UserCreateRequest $request
     *
     * @return \Response
     */
    public function store(UserCreateRequest $request)
    {
        $input = $request->only('email', 'activated', 'password');
        $user_info = $request->all();

        if (!$this->validateImage('avatar')) {
            FlashMessages::add('warning', trans('messages.bad image'));

            return Redirect::back()->withInput($input);
        }

        DB::beginTransaction();

        try {
            $user = Sentry::createUser($input);
            $user->activated = $input['activated'];
            $user->save();

            $this->_processGroups($user, $request->get('groups', []));

            $this->_processInfo($user, $user_info);

            $this->processFields($user);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.user.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput(array_merge($input, $user_info));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     *
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = User::with(['info', 'fields'])->whereId($id)->firstOrFail();
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.user.index');
        }

        $this->data('page_title', '"'.$model->getFullName().'"');

        $this->breadcrumbs(trans('labels.user_edit'));

        $this->fillAdditionalTemplateData(__FUNCTION__, $model);

        $this->data('model', $model);

        return $this->render('views.user.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                              $id
     * @param \App\Http\Requests\Backend\User\UserUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, UserUpdateRequest $request)
    {
        if (!$this->user->hasAccess('superuser') && (!$this->user->hasAccess('user.write') || $this->user->id != $id)) {
            FlashMessages::add('warning', trans('messages.you can not update others users'));

            return Redirect::route('admin.user.index');
        }

        try {
            $user = User::with(['info', 'fields'])->whereId($id)->firstOrFail();
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.user.index');
        }

        $input = $request->only('email', 'activated');
        $user_info = $request->all();

        if (!$this->validateImage('avatar')) {
            FlashMessages::add('warning', trans('messages.bad image'));

            return Redirect::back()->withInput($input);
        }

        DB::beginTransaction();

        try {
            $user->activated = $input['activated'];
            $user->update($input);

            $this->_processGroups($user, $request->get('groups', []));

            $this->_processInfo($user, $user_info);

            $this->processFields($user);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.user.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput(array_merge($input, $user_info));
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
            $user = Sentry::getUserProvider()->findById($id);

            $user->delete();
        } catch (UserNotFoundException $e) {
            FlashMessages::add('error', trans("User was not found."));
        }

        return Redirect::route('admin.user.index');
    }

    /**
     * @param int $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getNewPassword($id = 0)
    {
        $model = Sentry::getUserProvider()->findById($id);

        if (!$model) {
            FlashMessages::add('error', trans("messages.record_not_found"));

            return Redirect::route('admin.user.index');
        }

        $this->data('model', $model);

        $this->data('page_title', trans('labels.password_edit'));

        return $this->render('views.user.new_password');
    }

    /**
     * @param                       $id
     * @param PasswordChangeRequest $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postNewPassword($id, PasswordChangeRequest $request)
    {
        $responce = ($request->only('password', 'password_confirmation'));

        try {
            // Find the user using the user id
            $user = Sentry::getUserProvider()->findById($id);
            // Update the user details
            $user->password = $responce['password'];

            // Update the user
            if ($user->save()) {
                FlashMessages::add("success", trans("messages.save_ok"));

                return Redirect::route('admin.user.edit', $id);
            } else {
                // User information was not updated
                FlashMessages::add('error', trans("messages.save_failed"));
            }
        } catch (UserExistsException $e) {
            FlashMessages::add('error', trans("messages.login_already_exists"));
        } catch (UserNotFoundException $e) {
            FlashMessages::add('error', trans("messages.record_not_found"));
        }

        return Redirect::back()->withInput();
    }

    /**
     * @param      $function
     * @param null $model
     */
    public function fillAdditionalTemplateData($function, $model = null)
    {
        //user_groups - for exists user
        switch ($function) {
            case 'create' :
                $this->data('user_groups', []);
                break;
            case 'edit' :
                $user_groups = $model->getGroups();
                $this->data('user_groups', $user_groups->pluck('id')->toArray());
                break;
        }

        //set users groups
        $list = Sentry::getGroupProvider()->findAll();
        $groups = [];
        foreach ($list as $item) {
            $groups[$item['id']] = $item['name'];
        }
        $this->data('groups', $groups);

        //set users genders
        $genders = [];
        foreach (UserInfo::$genders as $gender) {
            $genders[$gender] = trans('labels.'.$gender);
        }
        $this->data('genders', $genders);

        //field types
        $field_types = ['' => trans('labels.please_select')];
        foreach (Field::$types as $type => $key) {
            $field_types[$key] = trans('labels.'.$type);
        }
        $this->data('field_types', $field_types);
    }

    /**
     * @param $usersId
     */
    public function getUsersAjax($usersId)
    {
        $arr['results'] = [];

        if (isset($_GET['term']) && !empty($_GET['term'])) {
            $users = User::where('email', 'like', $_GET['term'].'%')->where('id', '<>', $usersId)->get();

            foreach ($users as $user) {
                $arr['results'][] = [
                    'textForList' => "<span>{$user->email}</span>",
                    'text'        => $user->email,
                    'id'          => $user->id,
                ];
            }
        }

        print json_encode($arr);

        exit;
    }

    /**
     * @param \App\Models\User $user
     * @param array            $groups
     */
    private function _processGroups(User $user, $groups = [])
    {
        if ($this->user->hasAccess('user.group.write')) {
            $user->groups()->sync($groups);
        }
    }

    /**
     * @param \App\Models\User $user
     * @param array            $user_info
     *
     * @throws \App\Exceptions\ImageSaveException
     */
    private function _processInfo(User $user, $user_info = [])
    {
        $user_info['id'] = $user->id;
        $this->setImage($user_info, 'avatar', $this->module);

        $info = $user->info()->first();
        if (empty($info)) {
            $info = new UserInfo();
            $info->fill($user_info);
            $user->info()->save($info);
        } else {
            $info->update($user_info);
        }
    }
}
