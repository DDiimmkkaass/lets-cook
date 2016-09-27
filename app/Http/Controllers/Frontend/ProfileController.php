<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\BasketSubscribeDeleted;
use App\Events\Frontend\BasketSubscribeUpdated;
use App\Http\Requests\Frontend\BasketSubscribe\BasketSubscribeUpdateRequest;
use App\Http\Requests\Frontend\User\UserPasswordUpdateRequest;
use App\Http\Requests\Frontend\User\UserUpdateRequest;
use App\Models\City;
use App\Models\UserCoupon;
use App\Models\UserInfo;
use App\Services\UserService;
use Cartalyst\Sentry\Users\WrongPasswordException;
use DB;
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
     * @param \App\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();
        
        $this->userService = $userService;
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        Meta::title($this->user->getFullName());
        
        $this->data('user_coupons', UserCoupon::with(['coupon', 'orders'])->whereUserId($this->user->id)->get());
    
        $this->data('profile_css_class', 'profile-main');
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function ordersIndex()
    {
        $this->data(
            'active_orders',
            $this->userService->getOrders($this->user->id, ['changed', 'paid', 'processed'], ['recipes'])
        );
        
        $this->data('data_tab', 'my-orders');
        $this->data('profile_css_class', 'profile-orders');
        
        return $this->render($this->module.'.orders_index');
    }
    
    /**
     * @param \App\Http\Requests\Frontend\BasketSubscribe\BasketSubscribeUpdateRequest $request
     *
     * @return array
     */
    public function updateSubscribe(BasketSubscribeUpdateRequest $request)
    {
        try {
            $subscribe = $this->user->subscribe()->firstOrFail();
            
            DB::beginTransaction();
            
            $subscribe->fill($request->all());
            $subscribe->save();
            
            $subscribe->additional_baskets()->sync($request->get('baskets', []));
            
            event(new BasketSubscribeUpdated($subscribe));
            
            DB::commit();
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.basket subscription successfully saved'),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @return array
     */
    public function deleteSubscribe()
    {
        try {
            DB::beginTransaction();
            
            $subscribe = $this->user->subscribe()->firstOrFail();
            
            $subscribe->delete();
            
            event(new BasketSubscribeDeleted($this->user));
            
            DB::commit();
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.basket subscription successfully deleted'),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $this->data('profile_css_class', 'profile-edit');
        $this->data('page_title', trans('front_labels.profile_editing'));
        
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
            
            DB::beginTransaction();
            
            $this->userService->update($model, $input);
            
            DB::commit();
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.changes successfully saved'),
            ];
        } catch (Exception $e) {
            DB::rollBack();
    
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
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
            $genders[$gender] = trans('front_labels.gender_'.$gender);
        }
        $this->data('genders', $genders);
    
        $this->data('cities', City::positionSorted()->nameSorted()->get());
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