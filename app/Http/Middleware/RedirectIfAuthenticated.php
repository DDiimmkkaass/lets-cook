<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Middleware;

use Closure;
use FlashMessages;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Sentry;

/**
 * Class RedirectIfAuthenticated
 * @package App\Http\Middleware
 */
class RedirectIfAuthenticated
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     *
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
        if (Sentry::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(json_encode([
                    'status' => 'notice',
                    'message' => trans('messages.guest middleware error message')
                ]), 200);
            } else {
                FlashMessages::add('notice', trans('messages.guest middleware error message'));

                return redirect(localize_url('/'));
            }
        }

        return $next($request);
    }
}
