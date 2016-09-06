<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 31.03.16
 * Time: 15:19
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ViewComposer
 * @package App\Http\Middleware;
 */
class ViewComposerServiceProvider extends ServiceProvider
{
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('news/partials/filters', '\App\Http\ViewComposers\NewsComposer');
        view()->composer('article/partials/filters', '\App\Http\ViewComposers\ArticleComposer');
        view()->composer('recipe/partials/filters', '\App\Http\ViewComposers\RecipeComposer');
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}