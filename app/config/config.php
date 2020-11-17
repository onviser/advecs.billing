<?php declare(strict_types=1);

return [
    'db-billing' => [
        'host'         => '%DB_BILLING_HOST%',
        'port'         => '%DB_BILLING_PORT%',
        'user'         => '%DB_BILLING_USER%',
        'pass'         => '%DB_BILLING_PASS%',
        'name'         => 'billing',
        'table-prefix' => 'billing_'
    ],
    'app'        => [
        'debug' => '%APP_DEBUG%',
    ]
];