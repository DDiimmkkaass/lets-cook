<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.10.15
 * Time: 14:36
 */

namespace App\Http\Middleware;

use Carbon;
use Closure;

/**
 * Class PrepareWeeklyDates
 * @package App\Http\Middleware
 */
class PrepareWeeklyDates
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
        $input = $request->all();

        $input = $this->_process($input);

        $request->merge($input);

        return $next($request);
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function _process($array = [])
    {
        $_dates = [];

        if (!empty($array['week']) & preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}\s\-\s[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/', $array['week'])) {
            $dates = explode(' - ', $array['week']);

            $_dates = [
                'started_at' => Carbon::createFromFormat('d.m.Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s'),
                'ended_at'   => Carbon::createFromFormat('d.m.Y', $dates[1])->startOfDay()->format('Y-m-d H:i:s'),
            ];
        }

        return array_merge($array, $_dates);
    }
}
