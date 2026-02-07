<?php

declare(strict_types=1);

$defaultHost = file_exists('/.dockerenv') ? 'db' : '127.0.0.1';

return [
    'host' => getenv('DB_HOST') ?: $defaultHost,
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'name' => getenv('DB_NAME') ?: 'fooddb',
    'user' => getenv('DB_USER') ?: 'app_user',
    'password' => getenv('DB_PASSWORD') ?: 'app_password',
];
