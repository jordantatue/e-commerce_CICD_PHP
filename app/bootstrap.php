<?php

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once BASE_PATH . '/app/Core/Helpers.php';
require_once BASE_PATH . '/app/Core/Database.php';
require_once BASE_PATH . '/app/Core/Validation.php';
require_once BASE_PATH . '/app/Core/Repositories.php';
require_once BASE_PATH . '/app/Core/Auth.php';
require_once BASE_PATH . '/app/Core/Uploads.php';
