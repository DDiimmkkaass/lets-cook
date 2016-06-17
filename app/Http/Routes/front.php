<?php

$router->group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect'],
    ],
    function () use ($router) {
        // home
        $router->any('/', ['as' => 'home', 'uses' => 'Frontend\PageController@getHome']);

        // 404
        $router->any('/not-found', ['as' => 'not_found', 'uses' => 'Frontend\PageController@notFound']);

        // pages
        $router->get(
            '/pages/{slug1?}/{slug2?}/{slug3?}/{slug4?}/{slug5?}',
            ['as' => 'pages.show', 'uses' => 'Frontend\PageController@getPage']
        );

        // news
        $router->get('news', ['as' => 'news.index', 'uses' => 'Frontend\NewsController@index']);
        $router->get('news/{slug}', ['as' => 'news.show', 'uses' => 'Frontend\NewsController@show']);

        // comments
        $router->group(
            [
                'prefix'     => 'comments',
                'middleware' => 'ajax',
            ],
            function () use ($router) {
                $router->get('/', ['as' => 'comments.index', 'uses' => 'Frontend\CommentController@index']);

                $router->post('/', ['as' => 'comments.store', 'uses' => 'Frontend\CommentController@store']);
            }
        );

        // likes
        $router->post(
            '/likes',
            ['middleware' => ['auth', 'ajax'], 'as' => 'likes.index', 'uses' => 'Frontend\LikeController@store']
        );

        // search
        $router->get('search', ['as' => 'search.index', 'uses' => 'Frontend\SearchController@index']);

        // faq
        $router->get('faq', ['as' => 'questions.index', 'uses' => 'Frontend\QuestionController@index']);

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
                    '{id}/show',
                    ['as' => 'profiles.show', 'uses' => 'Frontend\ProfileController@show']
                );

                $router->get(
                    'edit',
                    ['as' => 'profiles.edit', 'uses' => 'Frontend\ProfileController@edit']
                );
                $router->post(
                    'update',
                    ['as' => 'profiles.update', 'uses' => 'Frontend\ProfileController@update']
                );

                $router->get(
                    'edit/password',
                    ['as' => 'profiles.edit.password', 'uses' => 'Frontend\ProfileController@editPassword']
                );
                $router->post(
                    'update/password',
                    ['as' => 'profiles.update.password', 'uses' => 'Frontend\ProfileController@updatePassword']
                );
            }
        );
    }
);