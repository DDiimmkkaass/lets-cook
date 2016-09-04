<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],

        'App\Events\Backend\PageDelete' => [],
        
        'App\Events\Backend\NewsDelete' => [],

        'App\Events\Backend\ArticleDelete' => [],

        'App\Events\Backend\WeeklyMenuSaved' => [
            'App\Listeners\Events\Backend\UpdatePurchase',
        ],

        'App\Events\Backend\TmplOrderSuccessfullyPaid' => [
            'App\Listeners\Events\Backend\SendUserEmailAboutSuccessfullyPaymentOnTmplOrder',
        ],

        'App\Events\Backend\TmplOrderPaymentError' => [
            'App\Listeners\Events\Backend\SendUserEmailAboutPaymentErrorOnTmplOrder',
        ],
        
        'App\Events\Frontend\UserRegister' => [
            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewUser',
            'App\Listeners\Events\Frontend\SendUserActivationEmail',
        ],

        'App\Events\Frontend\NewComment' => [
            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewComment',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
        //
    }
}
