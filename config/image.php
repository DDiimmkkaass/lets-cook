<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    'allowed_image_file_type' => [
        'image/jpeg',
        'image/jpg',
        'image/png',
    ],

    'allowed_image_extension' => [
        'jpeg',
        'jpg',
        'png',
    ],

    'max_upload_width' => 3000, // pixels

    'max_upload_height' => 3000 // pixels
];
