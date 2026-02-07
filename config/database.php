<?php

declare(strict_types=1);

$envHost = trim((string) getenv('DB_HOST'));
$insideDocker = file_exists('/.dockerenv');
$defaultHost = $insideDocker ? 'db' : '127.0.0.1';

return [
    'host' => $envHost !== '' ? $envHost : $defaultHost,
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'name' => getenv('DB_NAME') ?: 'fooddb',
    'user' => getenv('DB_USER') ?: 'app_user',
    'password' => getenv('DB_PASSWORD') ?: 'app_password',
];
