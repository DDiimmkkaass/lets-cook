<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 31.07.15
 * Time: 12:31
 */

namespace App\Http\Middleware;

use App;
use Closure;

/**
 * Class SetSlug
 * @package App\Http\Middleware
 */
class SetSlug
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
        $name = $request->input(App::getLocale().'.name', '');
        $slug = $request->input('slug', '');

        $request->request->set('slug', !empty($slug) ? $slug : (!empty($name) ? str_slug($name) : ''));

        return $next($request);
    }

}
