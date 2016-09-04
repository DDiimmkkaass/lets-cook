<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 31.07.15
 * Time: 12:31
 */

namespace App\Http\Middleware;

use Closure;
use FlashMessages;

/**
 * Class BeforeFinalisationDate
 * @package App\Http\Middleware
 */
class BeforeFinalisationDate
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
        $year = $request->route('year');
        $week = $request->route('week');
        
        if (after_finalisation($year, $week)) {
            FlashMessages::add('warning', trans('messages.this action is not allowed any more'));
            
            return redirect()->route('admin.purchase.edit', [$year, $week]);
        }
        
        return $next($request);
    }
}
