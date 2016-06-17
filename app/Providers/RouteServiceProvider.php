<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(
            [
                'middleware' => ['group' => 'web'],
            ],
            function ($router) {
                $router->group(
                    [
                        'namespace'  => $this->namespace,
                    ],
                    function ($router) {
                        include app_path('Http/Routes/admin.php');

                        include app_path('Http/Routes/auth.php');

                        include app_path('Http/Routes/front.php');
                    }
                );

                require app_path('Http/Routes/elfinder.php');
            }
        );
    }
}
