<?php namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\UserRegister;
use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\Controllers\SaveImageTrait;
use Carbon;
use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserAlreadyActivatedException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Http\Request;
use Mail;
use Sentry;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend
 */
class AuthController extends FrontendController
{
    
    use SaveImageTrait;
    
    /**
     * @var \App\Services\AuthService
     */
    protected $authService;
    
    /**
     * @var \App\Services\UserService
     */
    protected $userService;
    
    /**
     * AuthController constructor.
     *
     * @param \App\Services\AuthService $authService
     * @param \App\Services\UserService $userService
     */
    public function __construct(AuthService $authService, UserService $userService)
    {
        parent::__construct();
        
        $this->authService = $authService;
        $this->userService = $userService;
        
        $this->setRedirectTo();
    }
    
    /**
     * @param array $credentials
     *
     * @return mixed
     */
    public function postLogin($credentials = [])
    {
        $credentials = !empty($credentials) ? $credentials : [
            'email'    => request('email'),
            'password' => request('password'),
        ];
        
        try {
            if ($user = $this->authService->login($credentials)) {
                return [
                    'status'   => 'success',
                    'redirect' => session('redirect', false),
                    'message'  => trans('front_messages.you have successfully logged in'),
                ];
            }
            
            $error = trans('front_messages.access_denied');
        } catch (LoginRequiredException $e) {
            $error = trans('front_messages.enter your login');
        } catch (PasswordRequiredException $e) {
            $error = trans('front_messages.enter your password');
        } catch (WrongPasswordException $e) {
            $error = trans('front_messages.you have entered a wrong password');
        } catch (UserNotFoundException $e) {
            $error = trans('front_messages.user with such email was not found');
        } catch (UserNotActivatedException $e) {
            $error = trans('front_messages.user with such email was not activated');
        } catch (UserSuspendedException $e) {
            $error = trans('front_messages.user with such email was blocked');
            
            $user = User::where('email', $credentials['email'])->first();
            
            $throttle = Sentry::findThrottlerByUserId($user->id);
            
            $timestamp = strtotime($throttle->suspended_at);
            if ($timestamp) {
                $suspensionTime = $throttle->getSuspensionTime();
                $carbon = Carbon::createFromTimestamp($timestamp)->addMinutes($suspensionTime);
                
                $error .= ' '.trans('front_labels.to').' '.$carbon->format('d.m.Y H:i');
            }
        } catch (UserBannedException $e) {
            $error = trans('front_messages.user with such email was banned');
        } catch (Exception $e) {
            $error = trans('front_messages.an error has occurred, try_later');
        }
        
        return ['status' => 'error', 'message' => $error];
    }
    
    /**
     * @return mixed
     */
    public function getLogout()
    {
        Sentry::logout();
        
        FlashMessages::add('notice', trans('front_messages.you have successfully logout'));
        
        return redirect()->home();
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Auth\UserRegisterRequest $request
     * @param \App\Services\AuthService                            $authService
     *
     * @return mixed
     */
    public function postRegister(UserRegisterRequest $request, AuthService $authService)
    {
        DB::beginTransaction();
        
        try {
            $input = $this->authService->prepareRegisterInput($request);
            
            $user = $authService->register($input);
            
            event(new UserRegister($user, $input));
            
            DB::commit();
            
            return [
                'status'   => 'success',
                'message'  => trans('front_messages.user register success message'),
                'redirect' => $this->getRedirectTo(),
            ];
        } catch (Exception $e) {
            $message = trans('front_messages.user register error');
        }
        
        DB::rollBack();
        
        return ['status' => 'error', 'message' => $message];
    }
    
    /**
     * @param string $email
     * @param string $code
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getActivate($email, $code)
    {
        try {
            $user = Sentry::findUserByLogin($email);
            
            if ($user->attemptActivation($code)) {
                FlashMessages::add(
                    'success',
                    trans('front_messages.congratulations, you have successfully activate your account')
                );
                
                Sentry::login($user);
                
                return redirect()->route('profiles.edit.password');
            } else {
                $error = trans('front_messages.user activation failed, wrong activation code');
            }
        } catch (UserNotFoundException $e) {
            $error = trans('front_messages.user with such email was not found');
        } catch (UserAlreadyActivatedException $e) {
            $error = trans('front_messages.user with such email already activated');
        } catch (Exception $e) {
            $error = trans('front_messages.user activation failed, try again later');
        }
        
        FlashMessages::add('error', $error);
        
        return redirect()->home();
    }
    
    /**
     * @param Request $request
     *
     * @return array
     */
    public function postRestore(Request $request)
    {
        $email = $request->get('email');
        
        try {
            $user = Sentry::findUserByLogin($email);
    
            Mail::queue(
                'emails.auth.restore',
                ['email' => $email, 'token' => $user->getResetPasswordCode()],
                function ($message) use ($user) {
                    $message->to($user->email, $user->getFullName())
                        ->subject(trans('front_subjects.password_restore_subject'));
                }
            );
    
            return [
                'status'  => 'success',
                'message' => trans('front_messages.password restore message'),
            ];
        } catch (UserNotFoundException $e) {
            $error = trans('front_messages.user with such email was not found');
        } catch (Exception $e) {
            $error = trans('front_messages.an error has occurred, please reload the page and try again');
        };
        
        return [
            'status'  => 'error',
            'message' => $error,
        ];
    }
    
    /**
     * @param string $email
     * @param string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getReset($email = '', $token = '')
    {
        try {
            $user = Sentry::findUserByLogin($email);
            
            if ($user->checkResetPasswordCode($token)) {
                $password = str_random(config('auth.passwords.min_length'));
                
                if ($user->attemptResetPassword($token, $password)) {
                    $user->activated = true;
                    $user->save();
                    
                    Mail::queue(
                        'emails.auth.reset',
                        ['email' => $email, 'password' => $password],
                        function ($message) use ($user) {
                            $user = User::find($user->id);
                            
                            $message->to($user->email, $user->getFullName())
                                ->subject(trans('front_subjects.password_reset_success_subject'));
                        }
                    );
                    
                    FlashMessages::add(
                        'success',
                        trans('front_messages.password restore success message')
                    );
                    
                    return redirect()->home();
                } else {
                    $error = trans('front_messages.you have entered an invalid code');
                }
            } else {
                $error = trans('front_messages.you have entered an invalid code');
            }
        } catch (UserNotFoundException $e) {
            $error = trans('front_messages.user with such email was not found');
        } catch (Exception $e) {
            $error = trans('front_messages.an error has occurred, try_later');
        }
        
        FlashMessages::add('error', $error);
        
        return redirect()->home();
    }
    
    /**
     * set redirect after register login
     */
    private function setRedirectTo()
    {
        if (
            url()->previous() !== url()->current() &&
            strpos(url()->previous(), '/auth/') === false &&
            strpos(url()->previous(), '/profiles/') &&
            check_local()
        ) {
            session()->put('returnTo', url()->previous());
        }
    }
    
    /**
     * @return string
     */
    private function getRedirectTo()
    {
        $url = session('returnTo', false);
        
        if ($url) {
            session()->forget('returnTo');
        }
        
        return localize_url($url ? : url('/'));
    }
}