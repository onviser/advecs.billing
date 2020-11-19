<?php declare(strict_types=1);

return [
    'db-billing' => [
        'host'         => '%DB_BILLING_HOST%',
        'port'         => '%DB_BILLING_PORT%',
        'user'         => '%DB_BILLING_USER%',
        'pass'         => '%DB_BILLING_PASS%',
        'name'         => 'billing',
        'table-prefix' => 'billing_',
    ],
    'pscb'       => [
        'url'         => 'https://oosdemo.pscb.ru/',
        'marketPlace' => '159846510',
        'secretKey'   => '6ad7b354d3baf27949f0c5abb147d5214e68cc08469849fb3d8715dcae28d97e',
    ],
    'app'        => [
        'debug' => '%APP_DEBUG%',
    ]
];