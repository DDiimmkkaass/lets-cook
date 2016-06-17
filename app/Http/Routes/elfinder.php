<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 13.07.15
 * Time: 20:52
 */

$router->group(
    [
        'prefix' => 'admin',
    ],
    function ($router) {
        $router->get(
            'elfinder',
            array ('as' => 'admin.elfinder', 'uses' => '\Barryvdh\Elfinder\ElfinderController@showIndex')
        );
        $router->any(
            'elfinder/connector',
            array (
                'as'   => 'admin.elfinder.connector',
                'uses' => '\Barryvdh\Elfinder\ElfinderController@showConnector',
            )
        );
        $router->any(
            'elfinder/ckeditor4',
            array (
                'as'   => 'admin.elfinder.ckeditor4',
                'uses' => '\Barryvdh\Elfinder\ElfinderController@showCKeditor4',
            )
        );
    }
);