<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ThumbServiceProvider
 * @package App\Providers
 */
class ThumbServiceProvider extends ServiceProvider
{

    /**
     * register
     */
    public function register()
    {

        $this->app->bind('thumb', 'App\Classes\Thumb');
    }
}