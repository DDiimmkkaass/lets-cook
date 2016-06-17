<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Backend;

use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Redirect;
use Illuminate\Http\Request;
use Sentry;
use View;

/**
 * Class AuthController
 * @package App\Http\Controllers\Backend
 */
class AuthController extends BackendController
{

    /**
     * AuthController constructor.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        View::share('body_css_class', 'sidebar-collapse');
    }

    /**
     * @return $this
     */
    public function getLogin()
    {

        $this->_layout = 'admin.auth';

        return $this->render('views.auth.login');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {

        $credentials = [
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ];

        try {
            $user = Sentry::authenticate($credentials, $request->has('remember'));

            if ($user && $user->hasAccess('administrator')) {
                return Redirect::route('admin.home');
            }

            Sentry::logout();

            FlashMessages::add('error', trans("messages.access_denied"));
        } catch (Exception $e) {
            Sentry::logout();

            FlashMessages::add('error', $e->getMessage());
        }

        return Redirect::route('admin.login')->withInput();
    }

    /**
     * @return mixed
     */
    public function getLogout()
    {

        Sentry::logout();

        return Redirect::route('admin.login');
    }
}