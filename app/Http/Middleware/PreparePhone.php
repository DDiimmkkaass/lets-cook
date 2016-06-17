<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.10.15
 * Time: 14:36
 */

namespace App\Http\Middleware;

use App\Decorators\Phone;
use Closure;

/**
 * Class PreparePhone
 * @package App\Http\Middleware
 */
class PreparePhone
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
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->_process($value);
            } else {
                if ($key == 'phone') {
                    $array[$key] = (new Phone($value))->getDecorated();
                }
            }
        }

        return $array;
    }
}
