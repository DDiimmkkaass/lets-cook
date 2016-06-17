<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\User\UserPasswordUpdateRequest;
use App\Http\Requests\Frontend\User\UserUpdateRequest;
use App\Models\User;
use App\Models\UserInfo;
use App\Services\UserService;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Exception;
use FlashMessages;
use Meta;
use Sentry;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Frontend
 */
class ProfileController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'profile';
    
    /**
     * @var UserService
     */
    private $userService;

    /**
     * ProfileController constructor.
     *
     * @param \App\Services\UserService    $userService
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();

        $this->userService = $userService;
    }

    /**
     * public user page
     *
     * @param int $id
     *
     * @return $this
     */
    public function show($id)
    {
        $model = $this->userService->getUserById($id);

        abort_if(!$model, 404);

        $this->data('model', $model);

        Meta::title($model->getFullName());

        return $this->render($this->module.'.show');
    }
    
    /**
     * @return $this
     */
    public function index()
    {
        $user = $this->_getUser();

        view()->share('user', $user);

        Meta::title($user->getFullName());
        
        return $this->render($this->module.'.index');
    }

    /**
     * @return $this
     */
    public function edit()
    {
        $user = $this->_getUser();

        view()->share('user', $user);
        
        $this->fillAdditionalTemplateData();
        
        return $this->render($this->module.'.edit');
    }
    
    /**
     * @param \App\Http\Requests\Frontend\User\UserUpdateRequest $request
     *
     * @return mixed
     */
    public function update(UserUpdateRequest $request)
    {
        $model = $this->_getUser();
        
        try {
            $input = $this->userService->prepareInput($request);
            
            $this->userService->update($model, $input);
            
            FlashMessages::add('success', trans('messages.changes successfully saved'));

            return redirect()->route('profiles.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.an error has occurred, try_later'));
        }
        
        return redirect()->route('profiles.edit');
    }

    /**
     * @return $this
     */
    public function editPassword()
    {
        return $this->render($this->module.'.change_password');
    }

    /**
     * @param \App\Http\Requests\Frontend\User\UserPasswordUpdateRequest $request
     *
     * @return mixed
     */
    public function updatePassword(UserPasswordUpdateRequest $request)
    {
        $model = $this->_getUser();

        try {
            Sentry::findUserByCredentials(['email' => $model->email, 'password' => $request->get('old_password')]);

            $this->userService->updatePassword($model, $request->get('password'));

            FlashMessages::add('success', trans('messages.changes successfully saved'));

            return redirect()->route('profiles.index');
        } catch (WrongPasswordException $e) {
            FlashMessages::add('error', trans('messages.you have entered a wrong password'));
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.an error has occurred, try_later'));
        }

        return redirect()->back();
    }

    /**
     * fill additional template data
     */
    public function fillAdditionalTemplateData()
    {
        $genders = [];
        foreach (UserInfo::$genders as $gender) {
            $genders[$gender] = trans('labels.'.$gender);
        }
        $this->data('genders', $genders);
    }
    
    /**
     * get user by id or logout & abort if not find
     *
     * @param int|bool $id
     *
     * @return mixed
     */
    private function _getUser($id = false)
    {
        $user = $this->userService->getUserById($id ? : $this->user->id);

        if (!$user) {
            Sentry::logout();
            
            abort(404);
        }
        
        return $user;
    }
}