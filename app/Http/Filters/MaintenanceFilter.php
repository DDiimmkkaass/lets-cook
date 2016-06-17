<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Filters;

use Illuminate\Http\Response;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class MaintenanceFilter
 * @package App\Http\Filters
 */
class MaintenanceFilter
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
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        if ($this->app->isDownForMaintenance()) {
            return new Response('Be right back!', 503);
        }
    }
}
