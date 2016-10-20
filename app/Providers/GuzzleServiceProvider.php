<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class GuzzleServiceProvider
 * @package App\Providers
 */
class GuzzleServiceProvider extends ServiceProvider
{
    
    /**
     * register
     */
    public function register()
    {
        $this->app->bind(
            'guzzle',
            function () {
                return new Client();
            }
        );
    }
}