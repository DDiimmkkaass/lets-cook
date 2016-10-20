<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Test Mode
    | Режим тестирования
    |--------------------------------------------------------------------------
    |
    | After process test payment and confirm it by Yandex
    | Kassa, you must set this option value as false
    |
    | После проведения тестового платежа и подтверждения
    | этого платежа Яндекс Кассой, необходимо установить
    | значение этой опции в false
    |
    */
    'test_mode'     => env('YANDEX_KASSA_TEST_MODE', true),
    
    /*
    |--------------------------------------------------------------------------
    | Yandex Money shop parameters
    | Параметры магазина Яндекс Деньги
    |--------------------------------------------------------------------------
    |
    | In this section you should write yandex money requisites,
    | that you can get on Yandex Kassa official website, after
    | registering own shop
    |
    | Параметры, которые нужно заполнить ниже можно получить
    | в личном кабинете Яндекс Кассы, после регистрации
    | магазина
    |
    | @see https://money.yandex.ru/joinups
    |
    */
    'shop_id'       => env('YANDEX_KASSA_SHOP_ID', null),
    'sc_id'         => env('YANDEX_KASSA_SC_ID', null),
    
    /*
    |--------------------------------------------------------------------------
    | Shop Password
    | Секретное слово магазина (shoppassword)
    |--------------------------------------------------------------------------
    |
    | Secret word for generating md5-hash
    |
    | Секретное слово для формирования md5-хэша
    |
    | @see https://tech.yandex.com/money/doc/payment-solution/shop-config/parameters-docpage/
    |
    */
    'shop_password' => env('YANDEX_KASSA_SHOP_PASSWORD', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Payment types
    | Способы оплаты
    |--------------------------------------------------------------------------
    |
    | Payment types that will be given in payment form.
    | All available payment types you can find
    | in Yandex Kassa documentation
    |
    | Способы оплаты, которые будут предложены в форме
    | оплаты. Все доступные способы оплаты можно найти
    | в документации Яндекс Кассы
    |
    | @see https://tech.yandex.com/money/doc/payment-solution/reference/payment-type-codes-docpage/
    |
    */
    'payment_types' => [
        'PC',
        'AC',
        'MC',
        'GP',
        'WM',
        'SB',
        'MP',
        'AB',
        'MA',
        'PB',
        'QW',
        'KV',
        'QP',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Routes settings
    | Настройки путей
    |--------------------------------------------------------------------------
    |
    */
    'route'         => [
        'checkOrder'   => [
            'url' => '/payment/check',
        ],
        'cancelOrder'  => [
            'url' => '/payment/cancel',
        ],
        'paymentAviso' => [
            'url' => '/payment/aviso',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | MWS setting
    |--------------------------------------------------------------------------
    |
    */
    
    'currency' => 'RUB',
    
    'mws' => [
        'pay' => [
            'url'           => 'https://penelope.yamoney.ru/webservice/mws/api/repeatCardPayment',
            'test_mode_url' => 'https://penelope-demo.yamoney.ru:8083/webservice/mws/api/repeatCardPayment',
        ],
        
        'confirm' => [
            'url'           => 'https://penelope.yamoney.ru/webservice/mws/api/confirmPayment',
            'test_mode_url' => 'https://penelope-demo.yamoney.ru:8083/webservice/mws/api/confirmPayment',
        ],

        'cert' => env('MWS_CERT', base_path('cert/davaigotovit.cer')),

        'private_key' => env('MWS_PRIVATE_KEY', base_path('cert/private.key')),

        'cert_password' => env('MWS_CERT_PASSWORD'),
    ]
];
