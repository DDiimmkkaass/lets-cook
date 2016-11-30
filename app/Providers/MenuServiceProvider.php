<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Providers;

use App\Classes\Menu;
use Illuminate\Support\ServiceProvider;

/**
 * Class MenuServiceProvider
 * @package App\Providers
 */
class MenuServiceProvider extends ServiceProvider
{
    
    /**
     * register
     */
    public function register()
    {
        $this->app->singleton(
            'template_menu',
            function () {
                return new Menu();
            }
        );
    }
}