<?php

namespace App\Providers;

use App\Models\Card;
use App\Models\Order;
use App\Models\UserCoupon;
use App\Observers\CardObserver;
use App\Observers\OrderObserver;
use App\Observers\UserCouponObserver;
use App\Validators\AppValidator;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Barryvdh\TranslationManager\ManagerServiceProvider;
use Illuminate\Support\ServiceProvider;
use Validator;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->_registerValidators();
        
        $this->_registerModelsObservers();
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
    
    /**
     * Register any custom validators for application.
     */
    private function _registerValidators()
    {
        Validator::resolver(
            function ($translator, $data, $rules, $messages) {
                return new AppValidator($translator, $data, $rules, $messages);
            }
        );
    }
    
    /**
     * Register any models observers for application.
     */
    private function _registerModelsObservers()
    {
        Order::observe(OrderObserver::class);
        Card::observe(CardObserver::class);
        UserCoupon::observe(UserCouponObserver::class);
    }
}
