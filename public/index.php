<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

if (isAuthenticated()) {
    redirect('/recipes/home.php');
}

redirect('/auth/login.php');
