<?php

// home
$router->any('/', ['as' => 'home', 'uses' => 'Frontend\PageController@getHome']);

// 404
$router->any('/not-found', ['as' => 'not_found', 'uses' => 'Frontend\PageController@notFound']);

// 5xx
$router->any('/error', ['as' => 'server_error', 'uses' => 'Frontend\PageController@error']);

// pages
$router->get(
    '/pages/{slug1?}/{slug2?}/{slug3?}/{slug4?}/{slug5?}',
    ['as' => 'pages.show', 'uses' => 'Frontend\PageController@getPage']
);

// tags for filters
$router->get(
    'tags/{tag_category}/{module}',
    ['as' => 'tags.tags_by_category', 'uses' => 'Frontend\TagController@tagsByCategory']
);

// blog
$router->get('blog/{tag_id?}', ['as' => 'blog.index', 'uses' => 'Frontend\NewsController@index'])
    ->where(['tag_id' => '[0-9]+']);
$router->get('blog/{slug}', ['as' => 'blog.show', 'uses' => 'Frontend\NewsController@show']);

// articles
$router->get('articles/{tag_id?}', ['as' => 'articles.index', 'uses' => 'Frontend\ArticleController@index'])
    ->where(['tag_id' => '[0-9]+']);
$router->get('articles/{slug}', ['as' => 'articles.show', 'uses' => 'Frontend\ArticleController@show']);

// recipes
$router->get('recipes/{tag_id?}', ['as' => 'recipes.index', 'uses' => 'Frontend\RecipeController@index'])
    ->where(['tag_id' => '[0-9]+']);
$router->get('recipes/{recipe_id}/show', ['as' => 'recipes.show', 'uses' => 'Frontend\RecipeController@show']);

// baskets
$router->get('baskets/{week}', ['as' => 'baskets.index', 'uses' => 'Frontend\BasketController@index'])
    ->where('week', '(current|next)');
$router->get('baskets/{slug}/{portions?}/{week?}', ['as' => 'baskets.show', 'uses' => 'Frontend\BasketController@show'])
    ->where(['portions' => '[0-9]+', 'week' => '(current|next)']);
$router->get(
    'baskets/{slug}/repeat/{order_id}',
    [
        'as'   => 'baskets.repeat',
        'uses' => 'Frontend\BasketController@repeat',
    ]
)->where('order_id', '[0-9]+');

// search
$router->get('search', ['as' => 'search.index', 'uses' => 'Frontend\SearchController@index']);

// faq
$router->get('faq', ['as' => 'questions.index', 'uses' => 'Frontend\QuestionController@index']);

// contacts
$router->get('contacts', ['as' => 'contacts', 'uses' => 'Frontend\PageController@contacts']);

// feedback
$router->group(
    [
        'prefix'     => 'feedback',
        'middleware' => 'ajax',
    ],
    function () use ($router) {
        $router->post(
            '/',
            ['as' => 'feedback.store', 'uses' => 'Frontend\FeedbackController@store']
        );
    }
);

// subscribes
$router->post(
    '/subscribes',
    ['middleware' => 'ajax', 'as' => 'subscribes.store', 'uses' => 'Frontend\SubscribeController@store']
);

// comments
$router->group(
    [
        'prefix' => 'comments',
    ],
    function () use ($router) {
        $router->get(
            '{category_id?}/{tag_id?}',
            ['as' => 'comments.index', 'uses' => 'Frontend\CommentController@index']
        )->where(['category_id' => '[0-9]+', 'tag_id' => '[0-9]+']);
        
        $router->post(
            '/',
            [
                'as'         => 'comments.store',
                'middleware' => 'ajax',
                'uses'       => 'Frontend\CommentController@store',
            ]
        );
    }
);

//order
$router->group(
    [
        'prefix' => 'order',
    ],
    function () use ($router) {
        $router->post(
            '/store',
            ['as' => 'order.store', 'middleware' => ['ajax'], 'uses' => 'Frontend\OrderController@store']
        );
    
        $router->get('/success', ['as' => 'order.success', 'uses' => 'Frontend\OrderController@success']);
        
        $router->group(
            [
                'middleware' => 'auth',
            ],
            function () use ($router) {
                $router->get(
                    '/{order_id}/repeat',
                    ['as' => 'order.repeat', 'uses' => 'Frontend\OrderController@repeat']
                )
                    ->where('order_id', '[0-9]+');
                
                $router->get(
                    '/{order_id}/edit',
                    ['as' => 'order.edit', 'uses' => 'Frontend\OrderController@edit']
                )
                    ->where('order_id', '[0-9]+');
                $router->post(
                    '/{order_id}/update',
                    ['as' => 'order.update', 'middleware' => ['ajax'], 'uses' => 'Frontend\OrderController@update']
                )
                    ->where('order_id', '[0-9]+');
                
                $router->post(
                    '/{order_id}/delete',
                    ['as' => 'order.delete', 'middleware' => ['ajax'], 'uses' => 'Frontend\OrderController@delete']
                )
                    ->where('order_id', '[0-9]+');
            }
        );
    }
);

// profiles
$router->group(
    [
        'prefix'     => 'profiles',
        'middleware' => 'auth',
    ],
    function () use ($router) {
        $router->get(
            '/index',
            ['as' => 'profiles.index', 'uses' => 'Frontend\ProfileController@index']
        );
        
        $router->get(
            '/orders',
            ['as' => 'profiles.orders.index', 'uses' => 'Frontend\ProfileController@ordersIndex']
        );
        
        $router->group(
            [
                'prefix' => 'cards',
            ],
            function () use ($router) {
                $router->get(
                    '/',
                    ['as' => 'profiles.cards.index', 'uses' => 'Frontend\CardController@index']
                );
                
                $router->get(
                    '/create',
                    ['as' => 'profiles.cards.create', 'uses' => 'Frontend\CardController@create']
                );
                $router->post(
                    '/',
                    [
                        'as'         => 'profiles.cards.store',
                        'middleware' => 'ajax',
                        'uses'       => 'Frontend\CardController@store',
                    ]
                );
                
                $router->get(
                    '/{card_id}/edit',
                    ['as' => 'profiles.cards.edit', 'uses' => 'Frontend\CardController@edit']
                )->where('card_id', '[0-9]+');
                $router->post(
                    '/{card_id}',
                    ['as' => 'profiles.cards.update', 'uses' => 'Frontend\CardController@update']
                )->where('card_id', '[0-9]+');
                
                $router->post(
                    '/{card_id}/connect',
                    [
                        'as'         => 'profiles.cards.connect',
                        'middleware' => 'ajax',
                        'uses'       => 'Frontend\CardController@connect',
                    ]
                )->where('card_id', '[0-9]+');
                
                $router->post(
                    '/{card_id}/delete',
                    [
                        'as'         => 'profiles.cards.delete',
                        'middleware' => 'ajax',
                        'uses'       => 'Frontend\CardController@delete',
                    ]
                )->where('card_id', '[0-9]+');
            }
        );
        
        $router->post(
            '/basket-subscribes',
            ['as' => 'profiles.basket_subscribes.update', 'uses' => 'Frontend\ProfileController@updateSubscribe']
        );
        $router->post(
            '/basket-subscribes/delete',
            ['as' => 'profiles.basket_subscribes.delete', 'uses' => 'Frontend\ProfileController@deleteSubscribe']
        );
        
        $router->get(
            '/edit',
            ['as' => 'profiles.edit', 'uses' => 'Frontend\ProfileController@edit']
        );
        $router->post(
            '/update',
            ['as' => 'profiles.update', 'uses' => 'Frontend\ProfileController@update']
        );
        
        $router->get(
            '/set/password',
            ['as' => 'profiles.set.password', 'uses' => 'Frontend\ProfileController@setPassword']
        );
        $router->get(
            '/edit/password',
            ['as' => 'profiles.edit.password', 'uses' => 'Frontend\ProfileController@editPassword']
        );
        $router->post(
            '/update/password',
            ['as' => 'profiles.update.password', 'uses' => 'Frontend\ProfileController@updatePassword']
        );
    }
);

// profiles
$router->group(
    [
        'prefix'     => 'coupons',
        'middleware' => 'ajax',
    ],
    function () use ($router) {
        $router->group(
            [
                'middleware' => 'auth',
            ],
            function () use ($router) {
                $router->post(
                    '/',
                    ['as' => 'coupons.store', 'uses' => 'Frontend\CouponController@store']
                );
                
                $router->post(
                    '/make-default',
                    ['as' => 'coupons.make_default', 'uses' => 'Frontend\CouponController@makeDefault']
                );
            }
        );
        
        $router->post(
            '/check',
            ['as' => 'coupons.check', 'uses' => 'Frontend\CouponController@check']
        );
    }
);

$router->group(
    [
        'prefix' => 'payment',
    ],
    function () use ($router) {
        $router->post(
            '/check',
            ['as' => 'payment.check', 'uses' => 'Frontend\PaymentController@checkOrder']
        );
        $router->post(
            'aviso',
            ['as' => 'payment.aviso', 'uses' => 'Frontend\PaymentController@paymentAviso']
        );
        $router->post(
            'cancel',
            ['as' => 'payment.cancel', 'uses' => 'Frontend\PaymentController@cancelOrder']
        );
        
        $router->get(
            'success',
            ['as' => 'payment.success', 'uses' => 'Frontend\PaymentController@success']
        );
        $router->get(
            'fail',
            ['as' => 'payment.fail', 'uses' => 'Frontend\PaymentController@fail']
        );
        
        $router->get(
            'instruction',
            ['as' => 'payment.instruction', 'uses' => 'Frontend\PaymentController@instruction']
        );
        
        $router->post(
            'deposit_error',
            ['as' => 'payment.deposit_error', 'uses' => 'Frontend\PaymentController@depositError']
        );
    }
);