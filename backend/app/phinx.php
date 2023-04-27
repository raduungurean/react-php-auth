<?php

return [
    'paths' => [
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST'] ?? 'db',
            'name' => $_ENV['DB_DATABASE'] ?? 'app_database',
            'user' => $_ENV['DB_USERNAME'] ?? 'app_user',
            'pass' => $_ENV['DB_PASSWORD'] ?? 'app_password',
            'port' => 3306,
            'charset' => 'utf8mb4',
        ],
    ],
];
