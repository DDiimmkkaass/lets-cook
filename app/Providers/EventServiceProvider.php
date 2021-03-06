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

        'App\Events\Backend\CompletedOrderArchived' => [
            'App\Listeners\Events\Backend\ApplyLoyaltyProgram',
            'App\Listeners\Events\Backend\ApplyInviteFriendsProgram',
        ],
        
        'App\Events\Frontend\UserRegister' => [
            'App\Listeners\Events\Frontend\SendUserActivationEmail',
            'App\Listeners\Events\Frontend\GiveRegistrationCoupon',
            'App\Listeners\Events\Frontend\CreateInviteFriendCoupon',
            'App\Listeners\Events\Frontend\AddToClientsGroup',
            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewUser',
        ],

        'App\Events\Frontend\UserQuickRegister' => [
            'App\Listeners\Events\Frontend\SendUserActivationEmail',
            'App\Listeners\Events\Frontend\CreateInviteFriendCoupon',
            'App\Listeners\Events\Frontend\AddToClientsGroup',
            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewUser',
        ],

        'App\Events\Frontend\UserSocialRegister' => [
            'App\Listeners\Events\Frontend\GiveRegistrationCoupon',
            'App\Listeners\Events\Frontend\CreateInviteFriendCoupon',
            'App\Listeners\Events\Frontend\AddToClientsGroup',
            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewUser',
        ],

        'App\Events\Frontend\NewOrder' => [
            'App\Listeners\Events\Frontend\SendUserEmailWithOrderInfo',
            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewOrder',
        ],

        'App\Events\Frontend\NewComment' => [
            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewComment',
        ],

        'Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse' => [
            'App\Listeners\Events\Payments\CheckOrderRequisites',
            'App\Listeners\Events\Payments\CheckCardCreateRequisites',
        ],
        'Artem328\LaravelYandexKassa\Events\BeforeCancelOrderResponse' => [
            'App\Listeners\Events\Payments\CancelOrder',
        ],
        'Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse' => [
            'App\Listeners\Events\Payments\ChangeOrderStatusWhenPaymentSuccessful',
            'App\Listeners\Events\Payments\SaveUserCard',
        ],
        
        'App\Events\Frontend\BasketSubscribeUpdated' => [
            'App\Listeners\Events\Frontend\UpdateTmplOrders',
        ],
        'App\Events\Frontend\BasketSubscribeDeleted' => [
            'App\Listeners\Events\Frontend\DeleteUserTmplOrders',
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
