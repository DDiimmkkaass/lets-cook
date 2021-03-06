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
                        $router->post(
                            'register',
                            array (
                                'as'   => 'auth.register.post',
                                'uses' => 'Frontend\AuthController@postRegister',
                            )
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
                        
                        $router->get(
                            'social/{provider}',
                            ['as' => 'auth.social', 'uses' => 'Frontend\AuthController@social']
                        );
                    }
                );
    
                $router->group(
                    ['middleware' => 'admin.auth'],
                    function () use ($router) {
                        $router->get(
                            'admin-login/{user_id}',
                            ['as' => 'auth.admin_login', 'uses' => 'Frontend\AuthController@adminLogin']
                        )->where('user_id', '[0-9]+');
                    }
                );
            }
        );
    }
);