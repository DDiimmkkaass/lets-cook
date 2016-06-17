<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Middleware;

use Closure;
use Sentry;
use Session;
use URL;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\ResponseFactory;

/**
 * Class AdminAuthenticate
 * @package App\Http\Middleware
 */
class AdminAuthenticate
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Create a new filter instance.
     *
     * @param  Guard           $auth
     * @param  ResponseFactory $response
     *
     */
    public function __construct(Guard $auth, ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!Sentry::check()) {
            Session::put('redirect', URL::full());

            return $request->ajax() ? $this->response->make('Unauthorized', 401) : $this->response->redirectToRoute(
                'admin.login'
            );
        } else {
            $user = Sentry::getUser();

            // Check if the user is in the administrator group
            if (!$user || !$user->hasAccess('administrator')) {
                Sentry::logout();

                return $request->ajax() ? $this->response->make('Unauthorized', 401) : $this->response->redirectToRoute(
                    'admin.login'
                );
            }

            if ($redirect = Session::get('redirect')) {
                Session::forget('redirect');

                return $this->response->redirectTo($redirect);
            }
        }

        return $next($request);
    }
}
