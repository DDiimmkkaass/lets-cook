<?php

namespace App\Providers;

use App\Validators\DiffInDaysValidator;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Barryvdh\TranslationManager\ManagerServiceProvider;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new DiffInDaysValidator($translator, $data, $rules, $messages);
        });
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
