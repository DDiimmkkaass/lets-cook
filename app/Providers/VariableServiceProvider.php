<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Providers;

use App\Classes\Variable;
use Illuminate\Support\ServiceProvider;

/**
 * Class VariableServiceProvider
 * @package App\Providers
 */
class VariableServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(
            'variable',
            function () {
                return new Variable();
            }
        );
    }
    
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        variable()->mergeWithConfig();
    }
}