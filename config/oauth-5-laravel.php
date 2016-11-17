<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | oAuth Config
    |--------------------------------------------------------------------------
    */
    
    /**
     * Storage
     */
    'storage'   => '\\OAuth\\Common\\Storage\\Session',
    
    /**
     * Consumers
     */
    'consumers' => [
        
        'Vkontakte' => [
            'client_id'     => env('OAUTH_VKONTAKTE_CLIENT_ID'),
            'client_secret' => env('OAUTH_VKONTAKTE_CLIENT_SECRET'),
            'scope'         => ['offline', 'email'],
        ],
        
        'Facebook' => [
            'client_id'     => env('OAUTH_FACEBOOK_CLIENT_ID'),
            'client_secret' => env('OAUTH_FACEBOOK_CLIENT_SECRET'),
            'scope'         => ['email'],
        ],
    
    ],

];