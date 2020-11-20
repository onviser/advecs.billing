<?php declare(strict_types=1);

return [
    'db-billing' => [
        'host'         => '%DB_BILLING_HOST%',
        'port'         => '%DB_BILLING_PORT%',
        'user'         => '%DB_BILLING_USER%',
        'pass'         => '%DB_BILLING_PASS%',
        'name'         => '%DB_BILLING_NAME%',
        'table-prefix' => 'billing_',
    ],
    'pscb'       => [
        'url'         => 'https://oosdemo.pscb.ru/',
        'marketPlace' => '159846510',
        'secretKey'   => '111111', //%PSCB_KEY%
    ],
    'app'        => [
        'domain'   => '%APP_DOMAIN%',
        'protocol' => '%APP_PROTOCOL%',
        'debug'    => '%APP_DEBUG%',
    ]
];