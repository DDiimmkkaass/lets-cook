<?php

namespace App\Providers;

use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Barryvdh\TranslationManager\ManagerServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Permissions', 'App\Http\Providers\PermissionsProvider');

        $this->_registerDevDependencies();
    }

    /**
     * Register any application dev dependencies.
     *
     * @return void
     */
    private function _registerDevDependencies()
    {
        if (!$this->app->environment('production')) {
            $this->app->register(DebugbarServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(ManagerServiceProvider::class);
        }
    }
}
