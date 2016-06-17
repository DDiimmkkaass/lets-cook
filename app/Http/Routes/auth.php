<?php

$router->group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect'],
    ],
    function () use ($router) {
        $router->group(
            ['prefix' => 'auth'],
            function () use ($router) {
                $router->get(
                    'logout',
                    ['as' => 'auth.logout', 'uses' => 'Frontend\AuthController@getLogout']
                );

                $router->group(
                    ['middleware' => 'guest'],
                    function () use ($router) {
                        $router->get(
                            'register',
                            ['as' => 'auth.register', 'uses' => 'Frontend\AuthController@getRegister']
                        );

                        $router->post(
                            'register',
                            array (
                                'as'   => 'auth.register.post',
                                'uses' => 'Frontend\AuthController@postRegister',
                            )
                        );

                        $router->get(
                            'login',
                            ['as' => 'auth.login', 'uses' => 'Frontend\AuthController@getLogin']
                        );
                        $router->post(
                            'login',
                            ['as' => 'auth.login.post', 'uses' => 'Frontend\AuthController@postLogin']
                        );

                        $router->get(
                            'activate/{email}/{code}',
                            ['as' => 'auth.activate', 'uses' => 'Frontend\AuthController@getActivate']
                        );

                        $router->post(
                            'restore',
                            ['as' => 'auth.restore.post', 'uses' => 'Frontend\AuthController@postRestore']
                        );

                        $router->get(
                            'reset/{email}/{token}',
                            ['as' => 'auth.reset', 'uses' => 'Frontend\AuthController@getReset']
                        );
                    }
                );
            }
        );
    }
);