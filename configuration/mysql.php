<?php

// Permet de fonctionner en local ET via Docker Compose (host = service "db")
$defaultHost = file_exists('/.dockerenv') ? 'db' : 'localhost';

define('MYSQL_HOST', getenv('MYSQL_HOST') ?: $defaultHost);
define('MYSQL_PORT', (int) (getenv('MYSQL_PORT') ?: 3306));
define('MYSQL_NAME', getenv('MYSQL_NAME') ?: 'fooddb');
define('MYSQL_USER', getenv('MYSQL_USER') ?: 'tpweb');
define('MYSQL_PASSWORD', getenv('MYSQL_PASSWORD') ?: 'TyTy1234');
