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

/**
 * Class Suspend
 * @package App\Http\Middleware
 */
class Suspend
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
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(
                [
                    'status'  => 'error',
                    'message' => trans('front_messages.accepting orders suspended'),
                ]
            );
        } else {
            FlashMessages::add('error', trans('front_messages.accepting orders suspended'));
            
            return redirect()->back();
        }
    }
}
