<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Filters;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Session\TokenMismatchException;

/**
 * Class CsrfFilter
 * @package App\Http\Filters
 */
class CsrfFilter
{

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Routing\Route $route
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function filter(Route $route, Request $request)
    {
        if ($request->getSession()->token() != $request->input('_token')) {
            throw new TokenMismatchException;
        }
    }
}
