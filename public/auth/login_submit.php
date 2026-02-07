<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

if (requestMethod() !== 'POST') {
    redirect('/auth/login.php');
}

$input = validateLoginInput($_POST);
rememberOld($_POST, ['email']);

if ($input['errors'] !== []) {
    flash('error', implode(' ', $input['errors']));
    redirect('/auth/login.php');
}

if (!attemptLogin($input['email'], $input['password'])) {
    flash('error', 'Invalid login credentials.');
    redirect('/auth/login.php');
}

flash('success', 'Welcome back.');
redirect('/recipes/home.php');
