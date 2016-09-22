<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Sentry;

/**
 * Class CheckEmailExists
 * @package App\Http\Middleware
 */
class CheckEmailExists
{
    
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
            $user = User::whereEmail($request->get('email'))->count();
            
            if ($user) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(
                        [
                            'status' => 'success',
                            'show_auth_form' => true,
                            'message' => trans(
                                'front_messages.user with such email already exists, please :login or use another email',
                                ['login' => '<a href="#" class="show-auth-form">'.trans('front_labels.login').'</a>']
                            ),
                        ]
                    );
                }
            }
        }
        
        return $next($request);
    }
}
