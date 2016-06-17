<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class UnderMaintenance
 * @package App\Http\Middleware
 */
class UnderMaintenance
{

    /**
     * The application implementation.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new filter instance.
     *
     * @param  Application $app
     *
     */
    public function __construct(Application $app)
    {

        $this->app = $app;
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

        if ($this->app->isDownForMaintenance()) {
            return new Response('Be right back!', 503);
        }

        return $next($request);
    }
}