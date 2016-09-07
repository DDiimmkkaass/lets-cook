<?php

// home
$router->any('/', ['as' => 'home', 'uses' => 'Frontend\PageController@getHome']);

// 404
$router->any('/not-found', ['as' => 'not_found', 'uses' => 'Frontend\PageController@notFound']);

// pages
$router->get(
    '/pages/{slug1?}/{slug2?}/{slug3?}/{slug4?}/{slug5?}',
    ['as' => 'pages.show', 'uses' => 'Frontend\PageController@getPage']
);

// blog
$router->get('blog/{category_id?}/{tag_id?}', ['as' => 'blog.index', 'uses' => 'Frontend\NewsController@index'])
    ->where(['category_id' => '[0-9]+', 'tag_id' => '[0-9]+']);
$router->get('blog/{slug}', ['as' => 'blog.show', 'uses' => 'Frontend\NewsController@show']);

// articles
$router->get(
    'articles/{category_id?}/{tag_id?}',
    ['as' => 'articles.index', 'uses' => 'Frontend\ArticleController@index']
)
    ->where(['category_id' => '[0-9]+', 'tag_id' => '[0-9]+']);
$router->get('articles/{slug}', ['as' => 'articles.show', 'uses' => 'Frontend\ArticleController@show']);

// recipes
$router->get(
    'recipes/{category_id?}/{tag_id?}',
    ['as' => 'recipes.index', 'uses' => 'Frontend\RecipeController@index']
)
    ->where(['category_id' => '[0-9]+', 'tag_id' => '[0-9]+']);
$router->get('recipes/{recipe_id}/show', ['as' => 'recipes.show', 'uses' => 'Frontend\RecipeController@show']);

// baskets
$router->get('baskets', ['as' => 'baskets.index', 'uses' => 'Frontend\BasketController@index']);
$router->get('baskets/{id}', ['as' => 'baskets.show', 'uses' => 'Frontend\BasketController@show'])
    ->where('id', '[0-9]+');

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

//order
$router->get('order/{basket_id}', ['as' => 'order.index', 'uses' => 'Frontend\OrderController@index']);

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