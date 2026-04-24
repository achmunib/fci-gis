<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTMLTOPDF_BINARY', '/usr/local/bin/wkhtmltopdf'), // Sesuaikan path binary
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTMLTOIMAGE_BINARY', '/usr/local/bin/wkhtmltoimage'), // Sesuaikan path binary
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
];
